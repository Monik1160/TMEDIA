<template>
    <main>
        <div class="grid-container">
            <div class="card card-1">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="form-group col-sm-6">
                            <div>
                                <label>Cliente:</label>
                                <p>{{cliente.name}}</p>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Nombre de la campaña:</label>
                            <p>{{name}}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Notas</label>
                            <p>{{notes}}</p>
                        </div>
                        <!-- load the view from type and view_namespace attribute if set -->
                    </div>
                </div>
            </div>
            <div class="card card-2">
                <h5 class="card-header">
                    Detalle de Campaña
                </h5>
                <div class="card-body">
                    <div class="array-container form-group col-md-12">
                        <table v-show="details.length > 0"
                               class="table table-bordered table-striped m-b-0"
                               id="artes">
                            <thead>
                            <tr>
                                <td>
                                    Ruta
                                </td>
                                <td>
                                    Tipo de Publicidad
                                </td>
                                <td>
                                    Instalador Asignado
                                </td>
                                <td>
                                    Notas
                                </td>
                                <td>
                                    Bus Asignado
                                </td>
                                <td>
                                    Acciones
                                </td>
                            </tr>
                            </thead>
                            <tbody class="table-striped">
                            <tr class="array-row" id="arte_1" v-for="(detail, index) in details">
                                <td>
                                    {{ detail.ruta.name}}
                                    <!--                                        {{ detail.zona.name}}-->
                                </td>
                                <td>
                                    <p v-for="publicidad in detail.zona_publicitaria">
                                        {{publicidad.name}}
                                    </p>
                                </td>
                                <td>
                                    Juan Pablo Villaplana Corrales
                                </td>
                                <td>
                                    {{detail.notes}}
                                </td>
                                <td v-if="detail.bus">
                                    {{detail.bus.tipo_placa}}-{{detail.bus.placa}}
                                </td>
                                <td v-else>
                                    -
                                </td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm"
                                       v-on:click.prevent="editDetailsCampaign(detail)"><i
                                        class="fa fa-user"></i>
                                        Asignar Instalador</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card card-3" v-if="flag">
                <div class="card">
                    <div class="card-header">
                        Detalles
                    </div>
                    <div class="card-body">
                        <div class="row-design-container">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Seleccione Autobusero:</label>
                                    <multiselect v-model="autobusero"
                                                 deselect-label="Can't remove this value"
                                                 track-by="name" label="name"
                                                 placeholder="Seleccione Autobus"
                                                 :options="autobuseros"
                                                 :searchable="true"
                                                 :allow-empty="false"
                                                 @select="getBuses"/>
                                </div>
                                <div class="form-group">
                                    <label>Seleccione Bus:</label>
                                    <multiselect v-model="bus"
                                                 deselect-label="Can't remove this value"
                                                 track-by="fullPlate" label="Bus: "
                                                 placeholder="Seleccione Autobus"
                                                 :options="buses"
                                                 :searchable="true"
                                                 :allow-empty="false"
                                                 @select="fixBusArray"/>
                                </div>
                            </div>
                            <div class="col-md-12" v-if="bus_details != ''">
                                <div class="buses_detalles">
                                    <div class="col-sm-6">
                                        <div>
                                            <label>Carroceria del Bus: </label> <span>{{bus.carroceria}}</span>
                                        </div>
                                        <div v-if="bus.observaciones">
                                            <label>Observaciones: </label> <span>{{bus.observaciones}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="#" class="btn btn-primary btn-sm" v-on:click.prevent="addDetailCampaign"><i
                                            class="fal fa-bus"></i>
                                            Asignar Autobus</a>
                                    </div>
                                </div>
                                <div class="col-sm-12" v-if="bus_details != ''">
                                    <label>Fotografias:</label>
                                    <carousel :scrollPerPage="true" :perPageCustom="[[768, 1], [1024, 1]]">
                                        <slide v-for="foto in bus_details">
                                            <img :src="foto">
                                        </slide>
                                    </carousel>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>


<script>
    import axios from 'axios'
    import Multiselect from 'vue-multiselect'
    import moment from 'moment';
    import {Carousel, Slide} from 'vue-carousel';

    export default {
        components: {
            Multiselect,
            Carousel,
            Slide
        },
        data() {
            return {
                campaign_id: '',
                date: [],
                cliente: '',
                clientes: [],
                name: '',
                notes: '',
                details: [],
                bus: '',
                buses: [],
                detail_id: '',
                flag: false,
                bus_fotografias: [],
                bus_details: [],
                //Modal Items
                autobuseros: [],
                autobusero: '',
                zonas_publicitaria: [],
                route_id: '',
            }
        },
        created: function () {
            this.getCampaigns();
        },
        methods: {
            moment: function (d) {
                return moment(d);
            },

            getCampaigns: function () {
                let url = '/api/internal/campaigns';
                let campaign_id = document.getElementById('campaign_id').value;
                let data = {
                    campaign_id: campaign_id,
                };

                axios.get(url, {
                    params: data
                }).then((response => {
                    this.campaign_id = response.data.campaign_id;
                    this.cliente = response.data.client;
                    this.name = response.data.name;
                    this.notes = response.data.notes;
                    this.details = response.data.details;
                    this.artes = response.data.artes;
                }));
            },

            fixBusArray: function (bus) {
                this.bus_details = bus.fotografias.map((item) => (`https://com-publimediacr-app.s3.amazonaws.com/${item.image}`));
            },

            getBuses: function (autobusero) {
                this.bus = '';
                let data = {
                    id: autobusero.id,
                    zonas_publicitaria: this.zonas_publicitaria,
                    route: this.route_id,
                    start_date: this.date[0],
                    end_date: this.date[1],
                };
                let url = '/api/internal/campaign/bus';

                axios.get(url, {
                    params: data
                }).then((response => {
                    this.buses = response.data;
                }));
            },
            getAutobuseros: function (detail) {
                this.buses = [];
                let data = {
                    route_id: detail.ruta.id,
                };
                let url = '/api/internal/campaign/autobuseros';
                axios.get(url, {params: data}).then((response => {
                    this.autobuseros = response.data;
                    this.detail_id = detail.id;
                    this.zonas_publicitaria = detail.zona_publicitaria;
                    this.route_id = detail.ruta.id;
                }));
            },
            editDetailsCampaign: function (detail) {
                this.flag = true;
                this.bus_details = [];
                this.bus_fotografias = [];
                this.getAutobuseros(detail);
                this.bus = '';
                this.buses = [];
                this.route_id = '';
                this.autobusero = '';
                this.autobuseros = [];
                this.zonas_publicitaria = '';
            },
            addDetailCampaign: function (e) {
                e.preventDefault();
                let url = '/api/internal/campaign/bus/add';
                let data = {
                    detail_id: this.detail_id,
                    bus_id: this.bus.id,
                };

                axios.post(url, data).then((response => {
                    this.getCampaigns();
                    this.flag = false;
                    this.bus_details = [];
                    this.bus_fotografias = [];
                }));
            },
        }
    }
</script>

<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
    .card-1 {
        grid-area: campaing_info;
    }

    .card-2 {
        grid-area: campaing_details;
    }

    .card-3 {
        grid-area: campaing_bus;
        width: 85%;
    }

    .grid-container {
        display: grid;
        grid-template-areas:
    'campaing_info campaing_bus campaing_bus campaing_bus'
    'campaing_info campaing_bus campaing_bus campaing_bus'
    'campaing_details campaing_bus campaing_bus campaing_bus';
        grid-gap: 10px;
    }

    .card {
        margin-bottom: 0px;
        max-width: 100%;
        width: 794px;
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

    .buses_detalles {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
