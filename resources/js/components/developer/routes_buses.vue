<template>
    <main style="margin: 2em 0;">
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"></loading>
        <form method="post" enctype="multipart/form-data">
            <div class="container-fluid animated fadeIn">
                <div class="row">
                    <div class="col-md-12 bold-labels">
                        <!-- Default box -->
                        <div class="tab-container  mb-2">
                            <div class="tab-content p-0 ">
                                <div role="tabpanel" class="tab-pane  active" id="tab_general">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <h3>Rutas de Buses</h3>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Seleccione Autobusero:</label>
                                            <multiselect v-model="autobusero"
                                                         deselect-label="Can't remove this value"
                                                         track-by="name" label="name"
                                                         placeholder="Seleccione Autobusero"
                                                         :options="this.autobuseros" :searchable="true"
                                                         @select="getBuses"
                                                         :allow-empty="false" required/>
                                        </div>
                                        <div class="form-group col-sm-6" v-if="this.buses != ''">
                                            <label>Seleccione Bus:</label>
                                            <multiselect v-model="bus"
                                                         deselect-label="Can't remove this value"
                                                         track-by="fullPlate" label="fullPlate"
                                                         placeholder="Seleccione Autobus"
                                                         :options="buses"
                                                         :searchable="true"
                                                         :allow-empty="false"
                                                         @select="getRoutes"/>
                                            <main v-if="this.routes != ''">
                                                <label>Seleccione la rutas del bus: </label>
                                                <v-multiselect-listbox :options="this.routes"
                                                                       v-model="selectedRoutes"
                                                                       :reduce-display-property="(option) => option.label"
                                                                       :reduce-value-property="(option) => option.code"
                                                                       search-options-placeholder="Buscar Rutas"
                                                                       selected-options-placeholder="Buscar Rutas Seleccionadas"
                                                ></v-multiselect-listbox>
                                            </main>
                                        </div>
                                        <div class="col-md-6" v-if="bus != ''">
                                            <div class="buses_detalles">
                                                <div class="col-sm-6">
                                                    <div>
                                                        <label><strong>Placa:</strong> </label>
                                                        <span>{{bus.fullPlate}}</span>
                                                    </div>
                                                    <div>
                                                        <label><strong>Carroceria del Bus:</strong> </label> <span>{{bus.carroceria}}</span>
                                                    </div>
                                                    <div>
                                                        <label><strong>Tipo de Contrato:</strong> </label>
                                                        <span
                                                            :class="(bus.tipo_contrato == 'Consumo') ? 'text-danger font-weight-bold': ''">{{bus.tipo_contrato}}</span>
                                                    </div>
                                                    <div v-if="bus.observaciones">
                                                        <label><strong>Observaciones:</strong> </label> <span>{{bus.observaciones}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <label><strong>Fotografias:</strong></label>
                                                <main v-if="bus_fotos != ''">
                                                    <carousel :scrollPerPage="true"
                                                              :perPageCustom="[[768, 1], [1024, 1]]">
                                                        <slide v-for="foto in bus_fotos">
                                                            <img :src="foto">
                                                        </slide>
                                                    </carousel>
                                                </main>
                                                <main v-else>
                                                    El Autobus no tiene fotografías
                                                </main>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 " style="margin: 2em 0;">
                        <button class="btn btn-primary" type="submit" @click="saveAutobuserosRutas">Guardar Rutas de
                            Autobuseros
                        </button>
<!--                        <a class="btn btn-primary white" href="/autobuseros_rutas">Volver</a>-->
                    </div>
                </div>
            </div>
        </form>
    </main>
</template>
<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import vMultiselectListbox from 'vue-multiselect-listbox'
import 'vue-multiselect-listbox/dist/vue-multi-select-listbox.css';
import {Carousel, Slide} from "vue-carousel";


export default {
    components: {
        Multiselect,
        vMultiselectListbox,
        Loading,
        Carousel,
        Slide,
    },
    data() {
        return {
            autobusero: '',
            autobuseros: [],
            bus: '',
            buses: [],
            routes: [],
            selectedRoutes: [],
            isLoading: false,
            fullPage: true,
            bus_fotografias: [],
            bus_fotos: [],
        }
    },
    created: function () {
        this.loader();
        this.getAutobuseros();
    },
    methods: {
        loader: function () {
            this.isLoading = true;
        },

        onCancel() {
            this.isLoading = false;
        },

        fixBusArray: function (bus) {
            let bucket_url = bus.bucket;
            this.bus_fotos = bus.fotografias.map((item) => (`${bucket_url}/${item.image}`));
        },

        getAutobuseros: function () {
            let url = '/api/internal/campaign/autobuseros_all';

            axios.get(url).then((response => {
                this.autobuseros = response.data
                this.onCancel();
            }));
        },

        getBuses: function (autobusero, id) {
            this.bus = '';
            this.buses = [];
            this.routes = [];
            this.selectedRoutes = [];
            this.bus_fotografias = [];
            this.bus_fotos = [];

            let url = `/api/internal/campaign/buses_autobuseros/${autobusero.id}`;

            axios.get(url).then((response => {
                this.buses = response.data
                this.onCancel();
            }));
        },

        getRoutes: function (bus, id) {
            this.fixBusArray(bus)
            this.loader();

            let url = `/api/internal/campaign/ruta_all/${bus.id}`;


            axios.get(url, {
                params: {
                    bus: true,
                    autobusero_id: this.autobusero.id,
                }
            }).then((response => {
                this.routes = response.data.routes_all
                this.selectedRoutes = response.data.routes_selected
                this.onCancel();
            }));
        },
        saveAutobuserosRutas: function (e) {
            e.preventDefault();

            let params = {
                'bus_id': this.bus.id,
                'rutas_selected': this.selectedRoutes
            }

            this.loader();

            let url = '/api/internal/campaign/save_rutas_buses';

            axios.post(url, params).then((response => {
                new Noty({
                    title: "Rutas de Autobuseros",
                    text: "Rutas de Autobuseros Actualizas y Creadas",
                    type: "success"
                }).show();
                this.onCancel();
            }))
        },
    }
}
</script>

<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
.msl-multi-select {
    width: 100%;
}

.alert-danger {
    color: black;
    background-color: #cd201f57;
    border-color: #bd1d1d;
}

.VueCarousel {
    width: 100%;
}

.VueCarousel img {
    width: 100%;
    height: 390px;
}

.image-container[data-v-10e59822] {
    width: 100%;
}

</style>
