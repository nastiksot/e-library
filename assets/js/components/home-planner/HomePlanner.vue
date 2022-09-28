<template>
    <div class="home-planner">
        <v-btn icon @click="goLeft" class="home-planner__arrow-btn home-planner__arrow-btn--left">
            <img src="../../../../assets/images/home-left.png" alt="">
        </v-btn>

        <div id="map"></div>

        <v-btn icon @click="goRight" class="home-planner__arrow-btn home-planner__arrow-btn--right">
            <img src="../../../../assets/images/home-right.png" alt="">
        </v-btn>

        <div class="home-planner__navigation">
            <v-btn text small @click="showLayer(1)" :class="{'active' : currentLayer === 1 }" class="home-planner__navigation--layer1">
                Zugang
            </v-btn>
            <div class="home-planner__navigation--center">
                <v-btn text small @click="showLayer(2)" :class="{'active' : currentLayer === 2 }" class="home-planner__navigation--layer2">
                    Seitenansicht
                </v-btn>
            </div>
            <v-btn text small @click="showLayer(3)" :class="{'active' : currentLayer === 3 }" class="home-planner__navigation--layer3">
                Terrasse
            </v-btn>
        </div>

        <template v-if="startPortal">
            <template v-for="item in markerSettings['layer' + currentLayer]">
                <mounting-portal :append="true" :mount-to="'#show-case-' + item.id" v-if="idExists('show-case-' + item.id)">
                    <product-set-label
                        :dark="!item.recommended"
                        :inactive="!item.active"
                        :icon="item.icon"
                        :text="item.title"
                        :like="selectedProductSets.indexOf(item.id) !== -1"
                        :expanded="moreDetailsProductSetId === item.id"
                        @click="productSetClicked(item)"
                        @mouseover="productSetMouseOver(item.id)"
                        @mouseleave="productSetMouseLeave(item.id)"
                    />
                </mounting-portal>
            </template>
        </template>
    </div>
</template>

<script>
    import {mapActions, mapGetters, mapState} from 'vuex';
    import ProductSet from '../../models/product-set';
    import WishList from '../../models/wish-list';
    import WishListProductSet from '../../models/wish-list-product-set';
    import ProductSetLabel from '../product-set/ProductSetLabel';
    import L from 'leaflet';
    import ProductSetStore from '../product-set/store';
    import WishListStore from '../wish-list/store';
    import VideoPlayerStore from '../video-player/store';
    import _findIndex from 'lodash/findIndex';
    import _debounce from 'lodash/debounce';

    require('leaflet.markercluster/dist/leaflet.markercluster-src');

    export default {
        name: 'HomePlanner',

        props: {
            mode: {
                type: String,
                required: false,
                default: 'planner',
            },
        },

        components: {ProductSetLabel},

        data() {
            return {
                alert: true,

                map: null,
                crs: null,
                markerClusterGroup: null,

                bounds: null,

                startPortal: false,

                layer1: null,
                layer2: null,
                layer3: null,

                currentLayer: null,

                markerSettings: {
                    layer1: [],
                    layer2: [],
                    layer3: [],
                },

                markers: [],
            };
        },

        computed: {
            ...mapState('productSet', ['productSets', 'moreDetailsProductSetId']),
            ...mapState('wishList', ['wishList']),
            ...mapGetters('wishList', ['selectedProductSets']),

            isPlannerMode() {
                return this.mode === 'planner';
            },
        },

        watch: {
            wishList(data: WishList) {
                if (this.isPlannerMode) {
                    return;
                }

                this.prepareMarkerSettings(data.productSets);
            },

            productSets(data: Array) {
                if (!this.isPlannerMode) {
                    return;
                }

                this.prepareMarkerSettings(data);
            },
        },

        methods: {
            ...mapActions('productSet', ['setMoreDetailsProductSetId']),
            ...mapActions('videoPlayer', ['setVideoUrl']),

            idExists(id) {
                return document.getElementById(id);
            },

            tcEventClickOnPlannerProductSet(productSetTitle) {
                if (typeof (tc_events_global) !== 'undefined') {
                    try {
                        tc_events_global(this, 'homePlan_click', {
                            'evt_category': 'SmartHome_planner',
                            'evt_button_action': 'homePlan_click',
                            'evt_button_label': productSetTitle
                        });
                    } catch (error) {
                        console.error(error);
                    }
                }
            },

            productSetClicked(item: Object) {

                let el = this.getShowCaseById(item.id);

                if (el.classList.contains('most-top-fixed')) {
                    el.classList.remove('most-top-fixed');
                } else {
                    el.classList.add('most-top-fixed');
                }

                if (item.id === this.moreDetailsProductSetId) {
                    this.setMoreDetailsProductSetId(null);
                } else {
                    this.setMoreDetailsProductSetId(item.id);

                    this.tcEventClickOnPlannerProductSet(item.originalTitle);

                    setTimeout(() => {
                        // try to find on the CurrentLayer
                        let currentLayerIndex = _findIndex(this.markerSettings['layer' + this.currentLayer], {
                            id: item.id,
                            active: true,
                        });
                        if (currentLayerIndex === -1) {
                            // not found on the CurrentLayer
                            // try to find on another first layer
                            let layerKey = null;
                            for (let i = 1; i <= 3; i++) {
                                // skip the current layer
                                if (this.currentLayer === i) {
                                    continue;
                                }
                                let otherLayerIndex = _findIndex(this.markerSettings['layer' + i], {
                                    id: item.id,
                                    active: true,
                                });
                                if (otherLayerIndex !== -1) {
                                    layerKey = i;
                                }
                            }

                            // found on another layer
                            if (null !== layerKey) {
                                this.showLayer(layerKey);
                            }
                        }
                    }, 100);
                }
                this.setVideoUrl(null);
            },

            productSetMouseOver(id: Number) {
                this.getShowCaseById(id).classList.add('most-top');
            },

            productSetMouseLeave(id: Number) {
                this.getShowCaseById(id).classList.remove('most-top');
            },

            getShowCaseById(id: Number) {
                let els = document.getElementsByClassName('wrapper-show-case-' + id);

                if (els.length) {
                    return els.item(0);
                }

                return document.createElement('div');
            },

            goLeft() {
                this.currentLayer === 1 ? this.showLayer(3) : this.showLayer(this.currentLayer - 1);
            },

            goRight() {
                this.currentLayer === 3 ? this.showLayer(1) : this.showLayer(this.currentLayer + 1);
            },

            prepareMarkerSettings(data: Array) {
                if ('undefined' === typeof (data)) {
                    return;
                }

                const vm = this;

                for (let i = 1; i <= 3; i++) {
                    vm.markerSettings['layer' + i].splice(0, vm.markerSettings['layer' + i].length);
                }

                if (data.length) {
                    data.forEach((productSet: ProductSet | WishListProductSet) => {
                        for (let i = 1; i <= 3; i++) {
                            if (productSet['layer' + i + 'IconX'] && productSet['layer' + i + 'IconY']) {
                                vm.markerSettings['layer' + i].push({
                                    id: (productSet instanceof ProductSet) ? productSet.id : productSet.originalId,
                                    recommended: productSet.recommended,
                                    icon: productSet.icon,
                                    title: productSet.iconTitle ?? productSet.title,
                                    originalTitle: productSet.title,
                                    x: productSet['layer' + i + 'IconX'],
                                    y: productSet['layer' + i + 'IconY'],
                                    active: productSet['layer' + i + 'Active'],
                                });
                            }
                        }
                    });
                }

                this.showMarkers();
            },

            showLayer(num: Number) {
                for (let i = 1; i <= 3; i++) {
                    if (this.map.hasLayer(this['layer' + i])) {
                        this.map.removeLayer(this['layer' + i]);
                    }
                }

                this.currentLayer = num;
                this['layer' + num].addTo(this.map);

                this.showMarkers();
            },

            showMarkers() {
                this.startPortal = false;

                if (this.markerClusterGroup) {
                    this.map.removeLayer(this.markerClusterGroup);
                }

                this.markerClusterGroup = L.markerClusterGroup({
                    showCoverageOnHover: false,
                    maxClusterRadius: 40,
                });
                this.map.addLayer(this.markerClusterGroup);

                this.markerClusterGroup.on('clusterclick', () => {
                    this.startPortal = false;
                    this.$nextTick(() => {
                        this.startPortal = true;
                    });
                });

                this.markers.splice(0, this.markers.length);

                this.markerSettings['layer' + this.currentLayer].forEach((data) => {
                    this.markers.push(this.createMarker(data.x, data.y, 'show-case-' + data.id));
                });

                this.markers.forEach((marker) => {
                    marker.addTo(this.markerClusterGroup);
                });

                this.$nextTick(() => {
                    this.startPortal = true;
                });
            },

            createMarker(x: Number, y: Number, id: String) {
                return L.marker(
                    this.crs.unproject(L.point(x, y)),
                    {
                        icon: L.divIcon({
                            html: `<div id="${id}"></div>`,
                            className: `wrapper-${id}`,
                        }),
                    });
            },

            initMap: function () {
                let mapMinZoom = 3;

                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                        mapMinZoom = 1;
                        break;
                    case 'sm':
                        mapMinZoom = 2;
                        break;
                    case 'md':
                    case 'lg':
                        break;
                    case 'xl':
                        mapMinZoom = 4;
                }

                let mapExtent = [0.00000000, -4608.00000000, 8192.00000000, 0.00000000];
                let tileExtent = [0.00000000, -4608.00000000, 8192.00000000, 0.00000000];
                let mapMaxZoom = 6;
                let mapMaxResolution = 1.00000000;
                let mapMinResolution = Math.pow(2, mapMaxZoom) * mapMaxResolution;

                const crs = L.CRS.Simple;
                crs.transformation = new L.Transformation(1, -tileExtent[0], -1, tileExtent[3]);
                crs.scale = function (zoom) {
                    return Math.pow(2, zoom) / mapMinResolution;
                };
                crs.zoom = function (scale) {
                    return Math.log(scale * mapMinResolution) / Math.LN2;
                };

                const bounds = [
                    crs.unproject(L.point(mapExtent[2], mapExtent[3])),
                    crs.unproject(L.point(mapExtent[0], mapExtent[1])),
                ];

                this.bounds = bounds;
                this.crs = crs;

                const map = new L.Map('map', {
                    maxZoom: mapMaxZoom,
                    minZoom: mapMinZoom,
                    zoomSnap: 0.1,
                    zoomDelta: 0.2,
                    crs: crs,
                    maxBounds: bounds,
                    zoomControl: false,
                    attributionControl: false,
                    scrollWheelZoom: false,
                });

                L.control.zoom({
                    position: 'bottomright',
                }).addTo(map);

                this.map = map;

                let createLayer = function (num) {
                    return L.tileLayer('/tiles/map' + String(num) + '/{z}/{x}/{y}.png', {
                        tileSize: L.point(128.5, 128.5),
                        noWrap: true,
                        tms: false,
                        bounds: bounds,
                    });
                };

                this.layer1 = createLayer(1);
                this.layer2 = createLayer(2);
                this.layer3 = createLayer(3);
            },
        },

        mounted() {
            this.initMap();
            this.showLayer(2);

            this.map.on('zoomend', (event) => {
                this.startPortal = false;
                this.$nextTick(() => {
                    this.startPortal = true;
                });
            });

            // this function is no longer needed,
            // because the currently open ProductSet block completely overlaps the Map block
            // i.e. user can't click on the Map block when the ProductSet is open
            // this.map.on('click', (event) => {
            //     this.setMoreDetailsProductSetId(null);
            // });

            const resizeObserver = new ResizeObserver(_debounce(() => {
                this.map.invalidateSize();

                const target = this.map._getBoundsCenterZoom(this.bounds);
                this.map.setView(target.center, target.zoom + 0.2)
            }, 100));

            resizeObserver.observe(this.map._container);
        },

        beforeCreate() {
            if (!this.$store.hasModule('productSet')) {
                this.$store.registerModule('productSet', ProductSetStore);
            }

            if (!this.$store.hasModule('wishList')) {
                this.$store.registerModule('wishList', WishListStore);
            }

            if (!this.$store.hasModule('videoPlayer')) {
                this.$store.registerModule('videoPlayer', VideoPlayerStore);
            }
        },
    };
</script>

<style lang="scss">
    @import "~leaflet/dist/leaflet.css";
    @import "~leaflet.markercluster/dist/MarkerCluster.css";
    @import "~leaflet.markercluster/dist/MarkerCluster.Default.css";

    .most-top, .most-top-fixed {
        z-index: 9999 !important;
    }

    //.my-div-icon {
    //    margin-top: -49px !important;
    //}

    .leaflet-grab {
        cursor: unset;
    }
</style>

<style scoped lang="scss">
    @import "../../../scss/base";

    .home-planner {
        position: relative;
        @include calc("height", "100vh - 72px");
        z-index: 1;
        min-height: 624px;

        &__arrow-btn {
            position: absolute;
            z-index: 555;
            top: 0;
            bottom: 0;
            margin: auto;
            width: 42px;
            height: 42px;
            background: rgba(253, 253, 253, .5);
            border-radius: 4px;

            .v-icon {
                color: #fff;
            }

            &--left {
                left: 22px;
            }

            &--right {
                right: 22px;
            }
        }

        &__navigation {
            max-width: 300px;
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
            top: 14px;
            z-index: 555;
            @include flexbox();
            @include justify-content(space-between);
            background: #fff;
            border-radius: 4px;
            padding: 6px;

            .v-btn {
                color: #3C4F64;
                font-size: 14px !important;
                font-family: $fontSomfySansRegular;
                position: relative;
                border-radius: 4px;
                height: 31px;

                &.active {
                    color: #fff;
                    background: #FCAC22;
                }
            }
        }

        #map {
            width: 100%;
            height: 100%;
            //height: 800px;
            margin: 0;
            padding: 0;
            background-color: #b0b0b0
        }

        @media (max-width: 991px) {
            @include calc("height", "100vh - 380px");
            min-height: auto;
        }
    }
</style>
