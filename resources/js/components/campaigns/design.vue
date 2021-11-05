<template>
    <main>
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"></loading>
        <div class="grid-container">
            <div class="card item3">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="form-group col-sm-12">
                            <div>
                                <label>Cliente:</label>
                                <p>{{cliente.name}}</p>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
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
            <div class="card item2" v-if="flag">
                <div class="card-header">
                    Detalle del Bus
                </div>
                <div class="card-body">
                    <div class="row-design-container">
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <label>Carroceria del Bus: </label> <span>{{tipo_carroceria}}</span>
                            </div>
                            <div class="col-sm-6">
                                <label>Observaciones: </label> <span>{{observaciones}}</span>
                            </div>
                            <div class="col-sm-12">
                                <label>Fotografias:</label>
                                <main v-if="buses_fotos != ''">
                                    <carousel :scrollPerPage="true" :perPageCustom="[[768, 1], [1024, 1]]">
                                        <slide v-for="foto in buses_fotos">
                                            <img :src="foto">
                                        </slide>
                                    </carousel>
                                </main>
                                <main v-else>
                                    El buse no tiene Fotografias
                                </main>
                            </div>
                            <div class="col-sm-12">
                                <label>Diseños:</label>
                                <vue-upload-multiple-image
                                    @upload-success="uploadImageSuccess"
                                    @before-remove="beforeRemove"
                                    @edit-image="editImage"
                                    :data-images="buses_images"
                                    dragText="Haz click para subir los Diseños del Bus"
                                    browseText="Buscar"
                                >
                                    Empanada
                                </vue-upload-multiple-image>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card item1">
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
                                    Tipo de Publicidad
                                </td>
                                <td>
                                    Arte
                                </td>
                                <td>
                                    Notas
                                </td>
                                <td>
                                    Acciones
                                </td>
                            </tr>
                            </thead>
                            <tbody class="table-striped">
                            <tr class="array-row" id="arte_1" v-for="(detail, index) in details">
                                <td>
                                    <p v-for="publicidad in detail.zona_publicitaria">
                                        {{publicidad.name}}
                                    </p>
                                </td>
                                <td>
                                    <img :src="detail.arte">
                                </td>
                                <td>
                                    {{detail.notes}}
                                </td>
                                <td>
                                    <a href="#" class="btn btn-blue btn-sm"
                                       v-on:click.prevent="getBusInformation(detail.bus.id, detail.id)">
                                        <i class="fal fa-pencil"></i> Adjuntar Diseños</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="button_save">
            <a href="#" @click.prevent="saveDesign" class="btn btn-primary">Guardar Diseños</a>
        </div>
    </main>
</template>


<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import moment from 'moment';
import VueUploadMultipleImage from 'vue-upload-multiple-image';
import {Carousel, Slide} from 'vue-carousel';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
        components: {
            Multiselect,
            VueUploadMultipleImage,
            Carousel,
            Slide,
            Loading
        },
        props: {
            bucket: {
                type: String
            }
        },
        data() {
            return {
                isLoading: false,
                fullPage: true,
                campaign_id: '',
                details_id: '',
                cliente: '',
                name: '',
                notes: '',
                details: [],
                //Diseño
                bus_id: '',
                buses_images: [],
                flag: false,
                buses_fotos: [],
                index: null
            }
        },
        created: function () {
            this.loader()
            this.getCampaigns();
        },
        methods: {
            loader: function () {
                this.isLoading = true;
            },
            onCancel() {
                this.isLoading = false;
            },
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
                    this.details = response.data.details.map((item) => {
                        return {
                            arte: this.bucket + '/' + item.arte.image,
                            autobusero: item.autobusero,
                            bus: item.bus,
                            id: item.id,
                            notes: item.notes,
                            ruta: item.ruta,
                            zona: item.zona,
                            zona_publicitaria: item.zona_publicitaria
                        }
                    });
                    this.artes = response.data.artes;
                    this.onCancel();
                }));
            },

            getBusInformation: function (bus_id, detail_id) {
                let url = '/api/internal/campaign/bus-informacion';
                let data = {
                    bus_id: bus_id,
                    campaign_id: this.campaign_id
                };

                this.buses_images = [];
                this.buses_fotos = [];
                this.flag = false;

                this.details_id = detail_id;

                axios.get(url, {
                    params: data
                }).then((response => {
                    this.flag = true;
                    this.bus_id = response.data.bus_id;
                    this.bus_placa = response.data.fullPlate;
                    this.tipo_carroceria = response.data.carroceria;
                    this.observaciones = response.data.observaciones;
                    this.buses_fotos = response.data.fotografias.map((item) => (item.image));
                    this.buses_images = response.data.designs;
                }));
            },

            uploadImageSuccess(formData, index, fileList) {
                // Upload image api
                formData.append('bus_id', this.bus_id);
                formData.append('campaign_id', this.campaign_id);
                formData.append('detail_id', this.details_id);

                axios.post('/api/internal/campaign/designs', formData).then(response => {
                    fileList[index].design_id = response.data.id;
                })
            },
            beforeRemove(index, done, fileList) {
                var r = confirm("Desea Elimanar este Diseño?")
                if (r == true) {
                    let data = {
                        'design_id': fileList[index].design_id
                    }
                    axios.put('/api/internal/campaign/remove-designs', data).then(response => {
                        done()
                    })
                } else {
                }

            },
            editImage(formData, index, fileList) {
                console.log('edit data', formData, index, fileList)
            },
            saveDesign(){
                this.loader();
                window.location = '/campañas';
            }
        },
    }
</script>

<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
    .item1 {
        grid-area: header;
    }

    .item2 {
        grid-area: menu;
        width: 85%;
    }

    .item3 {
        grid-area: main;
    }

    /*.item4 { grid-area: right; }*/
    /*.item5 { grid-area: footer; }*/

    .grid-container {
        display: grid;
        grid-template-areas:
    'main menu menu menu'
    'main menu menu menu'
    'header menu menu menu';
        grid-gap: 10px;
    }

    .row {
        /*display: block !important;*/
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

    #artes img {
        width: 50%;
    }

    .form-group label{
        font-weight: bold;
    }

    .button_save{
        margin-top: 1em;
    }

</style>
