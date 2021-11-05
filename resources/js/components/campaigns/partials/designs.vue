<template>
    <main>
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"></loading>
        <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">

                        <div class="modal-header">
                            <slot name="header">
                                Detalle del Bus
                            </slot>
                        </div>

                        <div class="modal-body">
                            <div class="col-md-12 row">
                                <div class="col-sm-6">
                                    <label>Carroceria del Bus: </label> <span>{{ tipo_carroceria }}</span>
                                    <br>
                                    <label>Observaciones: </label> <span>{{ observaciones }}</span>
                                </div>
                                <div class="col-sm-12">
                                    <label>Diseños: </label>
                                    <carousel :scrollPerPage="true" :perPageCustom="[[768, 1], [1024, 1]]">
                                        <slide v-for="foto in buses_fotos">
                                            <img :src="foto.path">
                                        </slide>
                                    </carousel>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="modal-default-button btn btn-primary" @click="$emit('close')">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </main>
</template>
<script>


import axios from "axios";
import {Carousel, Slide} from 'vue-carousel';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Carousel,
        Slide,
        Loading
    },
    name: "Diseno",
    props: {
        busId: {
            type: Number,
            required: true
        },
        detailId: {
            type: Number,
            required: true
        },
        campaignId: {
            type: Number,
            required: true
        },
    },
    created() {
        this.loader()
        this.getBusInformation(this.busId, this.detailId);
    },
    data() {
        return {
            buses_fotos: [],
            observaciones: '',
            tipo_carroceria: '',
            bus_placa: '',
            campaign_id: this.campaignId,
            isLoading: false,
            fullPage: true,
        }
    },
    methods: {
        loader: function () {
            this.isLoading = true;
        },
        onCancel() {
            this.isLoading = false;
        },
        getBusInformation: function (bus_id, detail_id) {
            let url = '/api/internal/campaign/bus-informacion';
            let data = {
                bus_id: bus_id,
                campaign_id: this.campaign_id
            };
            this.detail_id = detail_id;
            this.buses_images = [];
            this.buses_fotos = [];
            this.flag = false;

            axios.get(url, {
                params: data
            }).then((response => {
                this.bus_placa = response.data.fullPlate;
                this.tipo_carroceria = response.data.carroceria;
                this.observaciones = response.data.observaciones;
                // this.buses_fotos = response.data.fotografias.map((item) => (`https://publimedia-bucket-s3.s3.amazonaws.com/${item.image}`));
                this.buses_fotos = response.data.designs;
                this.onCancel();
            }));
        },
    }
}
</script>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 50em;
    margin: 0px auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.modal-body {
    margin: 20px 0;
}

.modal-default-button {
    float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
    opacity: 0;
}

.modal-leave-active {
    opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
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
