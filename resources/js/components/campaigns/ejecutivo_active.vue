<template>
    <main>
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"
                 :on-cancel="onCancel"
        ></loading>
        <div class="row">
            <div class="col-md-12">
                <px-header></px-header>
            </div>
            <div class="card item1 col-md-12">
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
                                    Ruta
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
                                        {{ publicidad.name }}
                                    </p>
                                </td>
                                <td>
                                    {{ detail.ruta.name }}
                                </td>
                                <td>
                                    <img :src="'https://publimedia-bucket-s3.s3.amazonaws.com/'+detail.arte.image">
                                </td>
                                <td>
                                    {{ detail.notes }}
                                </td>
                                <td>
                                    <a href="#" class="btn btn-blue btn-sm"
                                       @click.prevent="showModalBus(detail.bus.id, detail.id)">
                                        <i class="fal fa-pencil"></i> Ver Diseños</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <main>
                        <diseno v-if="showModal && modalDetailId != '' && modalBusId != ''" @close="showModal = false"
                                :busId="modalBusId"
                                :detailId="modalDetailId" :campaignId="campaign_id"></diseno>
                    </main>
                </div>
            </div>
        </div>
        <div v-if="this.campaign_status == true">
            <button class="btn btn-primary" v-on:click.prevent="activeCampaign">Crear Tareas y Activar Campaña</button>
        </div>
        <div v-else>
            <a href="/campañas" class="btn btn-primary">Volver a Campañas</a>
        </div>
    </main>
</template>


<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import moment from 'moment';
import VueUploadMultipleImage from 'vue-upload-multiple-image';
import {Carousel, Slide} from 'vue-carousel';
import Diseno from "./partials/designs";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import PxHeader from "./PxHeader";


export default {
    components: {
        Multiselect,
        Carousel,
        Slide,
        Diseno,
        Loading,
        PxHeader
    },
    data() {
        return {
            isLoading: false,
            fullPage: true,
            campaign_id: '',
            cliente: '',
            name: '',
            notes: '',
            details: [],
            //Diseño
            detail_id: '',
            bus_id: '',
            buses_images: [],
            flag: false,
            buses_fotos: [],
            index: null,
            tarea: true,
            showModal: false,
            modalBusId: '',
            modalDetailId: '',
            campaign_status: '',
        }
    },
    created: function () {
        this.loader();
        this.getCampaigns();
        this.disable = this.block;
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
                this.campaign_status = response.data.status;
                this.onCancel();
            }));
        },

        showModalBus: function (bus_id, detail) {
            console.log(bus_id)
            this.modalBusId = bus_id;
            this.modalDetailId = detail;
            this.showModal = true;
        },

        activeCampaign: function () {
            this.loader();

            axios.post(`/api/internal/campaign/${this.campaign_id}/task/create`, {detail_id: this.detail_id}).then(response => {
                this.onCancel();
                window.location = '/campañas'
            })
                .catch(error => {
                    new Noty({
                        title: "No se pueden crear las Tareas",
                        text: error.response.data.error,
                        type: "error"
                    }).show();
                    this.onCancel();
                })
        },

        loader: function () {
            this.isLoading = true;
        },

        onCancel: function () {
            this.isLoading = false;
        }
    }
}
</script>

<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

#artes img {
    width: 20%;
}

.form-group label {
    font-weight: bold;
}

</style>
