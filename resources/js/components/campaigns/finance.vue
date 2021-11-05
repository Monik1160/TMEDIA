<template>
    <main>
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"
                 :on-cancel="onCancel"
        ></loading>
        <div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-general-tab" data-toggle="tab"
                       href="#nav-general" role="tab" aria-controls="nav-general"
                       aria-selected="true">Home</a>
                    <a class="nav-item nav-link" id="nav-finance-tab" data-toggle="tab"
                       href="#nav-finance" role="tab" aria-controls="nav-finance" aria-selected="false">Finanzas</a>
                </div>
            </nav>
            <form method="POST" enctype="multipart/form-data" @submit="updateCampaingRequest">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-general" role="tabpanel"
                         aria-labelledby="nav-home-tab">
                        <div class="container-fluid animated fadeIn">
                            <div class="row">
                                <div class="col-md-12 bold-labels">
                                    <div class="tab-container  mb-2">
                                        <div class="tab-content p-0 ">
                                            <div role="tabpanel" class="tab-pane  active" id="tab_general">
                                                <div class="row">
                                                    <div class="form-group col-sm-6">
                                                        <div>
                                                            <label>Cliente:</label>
                                                            <multiselect v-model="cliente"
                                                                         deselect-label="Can't remove this value"
                                                                         track-by="name" label="name"
                                                                         placeholder="Seleccione Cliente"
                                                                         :options="clientes" :searchable="true"
                                                                         :allow-empty="false" required
                                                                         :disabled="disableCampaignOnStatus(install_campaign_status)"
                                                                         />
                                                            <span v-if="errors.client"
                                                                  class="error text-danger">{{
                                                                    errors.client[0]
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label>Nombre de la campaña:</label>
                                                        <input type="text" name="name" value="" autocomplete="off"
                                                               class="form-control" v-model="name" required :disabled="disableCampaignOnStatus(install_campaign_status)">
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <label>Fechas de inicio y Finalizacion de la Campaña</label>
                                                        <br>
                                                        <date-picker v-model="date" range type="date|month" :disabled="disableCampaignOnStatus(install_campaign_status)" />
                                                        <br>
                                                        <span v-if="errors.date"
                                                              class="error text-danger">{{ errors.date[0] }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <div>
                                                            <label>Requiere desinstalación inmediata?</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input"
                                                                   id="requerie_desinstallion_1"
                                                                   name="requerie_desinstallion"
                                                                   value="0" checked=""
                                                                   v-model="requerie_desinstallion"
                                                                   :disabled="disableCampaignOnStatus(install_campaign_status)"
                                                                   >
                                                            <label
                                                                class="radio-inline form-check-label font-weight-normal"
                                                                for="requerie_desinstallion_1">No</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input"
                                                                   id="requerie_desinstallion_2"
                                                                   name="requerie_desinstallion"
                                                                   value="1" v-model="requerie_desinstallion"
                                                                   :disabled="disableCampaignOnStatus(install_campaign_status)"
                                                                   >
                                                            <label
                                                                class="radio-inline form-check-label font-weight-normal"
                                                                for="requerie_desinstallion_2">Si</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <div>
                                                            <label>Posible Renovación?</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input"
                                                                   id="requerie_desinstallion_1"
                                                                   name="posible_renovation"
                                                                   value="0" checked="" v-model="posible_renovation"
                                                                   :disabled="disableCampaignOnStatus(install_campaign_status)"
                                                                   >
                                                            <label
                                                                class="radio-inline form-check-label font-weight-normal"
                                                                for="requerie_desinstallion_1">No</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input"
                                                                   id="requerie_desinstallion_2"
                                                                   name="posible_renovation"
                                                                   value="1" v-model="posible_renovation"
                                                                   :disabled="disableCampaignOnStatus(install_campaign_status)"
                                                                   >
                                                            <label
                                                                class="radio-inline form-check-label font-weight-normal"
                                                                for="requerie_desinstallion_2">Si</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Monto de la Campaña:</label>
                                                        <div class="input-group mb-3">
                                                            <input type="number" class="form-control"
                                                                   v-model="total_monto"
                                                                   required
                                                                   :disabled="disableCampaignOnStatus(install_campaign_status)"
                                                                   >
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Comisión:</label>
                                                        <div class="input-group mb-3">
                                                            <input type="number" class="form-control"
                                                                   v-model="comision" required
                                                                   :disabled="disableCampaignOnStatus(install_campaign_status)"
                                                                   >
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label>Notas</label>
                                                        <textarea name="notes" placeholder="Notas internas"
                                                                  class="form-control" v-model="notes" :disabled="disableCampaignOnStatus(install_campaign_status)" />
                                                        <span v-if="errors.notes"
                                                              class="error text-danger">{{ errors.notes[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <h5 class="card-header">
                                    Artes
                                </h5>
                                <div class="card-body">
                                    <div class="array-container form-group col-md-12">
                                        <div class="col-md-12" v-if="!disableCampaignOnStatus(install_campaign_status)">
                                            <div class="row" style="margin-bottom: 30px;">
                                                <div class="col-md-12 alert alert-danger" v-if="errors.artes">
                                                    Debes Agregar Versión a la Campaña
                                                </div>
                                                <div class="col-md-6" >
                                                    <label>Nombre:</label>
                                                    <input type="text" name="name_version" value="" autocomplete="off"
                                                           class="form-control" v-model="name_version">
                                                    <span v-if="errors_arts"
                                                          class="text-danger">{{ errors_arts[0] }}</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Imagen:</label>
                                                    <input type="file" class="form-control" v-on:change="onImageChange">
                                                    <span v-if="errors_arts"
                                                          class="text-danger">{{ errors_arts[0] }}</span>
                                                </div>
                                                <div class="col-md-12 mt-4">
                                                    <a class="btn btn-secondary" v-on:click="addArtToCampaign">Agregar
                                                        Versión
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <table v-show="artes.length > 0"
                                               class="table table-bordered table-striped m-b-0"
                                               id="artes">
                                            <thead>
                                            <tr>
                                                <td>
                                                    Arte
                                                </td>
                                                <td>
                                                    Imagen
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody class="table-striped">
                                            <tr class="array-row" id="artes_version" v-for="arte in artes">
                                                <td>
                                                    {{ arte.name }}
                                                </td>
                                                <td v-if="arte.on_camping_id == null">
                                                    <img :src="arte.image" width="100px" height="100px"/>
                                                </td>
                                                <td v-if="arte.preview">
                                                    <img :src="arte.preview" width="100px" height="100px"/>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <h5 class="card-header">
                                    Detalle de Campaña
                                </h5>
                                <div class="card-body">
                                    <div class="array-container form-group col-md-12">
                                        <div class="col-md-12" v-if="!disableCampaignOnStatus(install_campaign_status)">
                                            <div class="row" style="margin-bottom: 30px;">
                                                <div class="col-md-12 alert alert-danger" v-if="errors.details">
                                                    Debes Agregar detalles a la Campaña
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Ruta:</label>
                                                    <multiselect v-model="ruta"
                                                                 deselect-label="Can't remove this value"
                                                                 track-by="name" label="name"
                                                                 placeholder="Seleccione Ruta"
                                                                 :options="rutas" :searchable="true"
                                                                 :allow-empty="false"/>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Tipo de Publicidad:</label>
                                                    <br>
                                                    <multiselect v-model="zonapublicitaria"
                                                                 deselect-label="Can't remove this value"
                                                                 track-by="name" label="name"
                                                                 placeholder="Seleccione Tipo de Publicidad"
                                                                 :options="zonaspublicitarias" :searchable="true"
                                                                 :allow-empty="false" :multiple="true"/>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Arte:</label>
                                                    <br>
                                                    <multiselect v-model="arte_ruta"
                                                                 deselect-label="Can't remove this value"
                                                                 track-by="name" label="name"
                                                                 placeholder="Seleccione Zona Publicitaria"
                                                                 :options="artes" :searchable="true"
                                                                 :allow-empty="false"/>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Notas</label>
                                                    <br>
                                                    <textarea style="width: 100%;" v-model="notes_details"/>
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <a class="btn btn-secondary" v-on:click="addDetailCampaign">Agregar
                                                        Detalle
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
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
                                                    Arte
                                                </td>
                                                <td>
                                                    Notas
                                                </td>
                                                <td v-if="!disableCampaignOnStatus(install_campaign_status)">
                                                    Acciones
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody class="table-striped">
                                            <tr class="array-row" id="arte_1" v-for="(detail, index) in details">
                                                <td>
                                                    {{ detail.ruta.name }}
                                                </td>
                                                <td>
                                                    <p v-for="publicidad in detail.zona_publicitaria">
                                                        {{ publicidad.name }}
                                                    </p>
                                                </td>
                                                <td>
                                                    {{ detail.arte.name }}
                                                </td>
                                                <td>
                                                    {{ detail.notes }}
                                                </td>
                                                <td style="text-align:center;" v-if="detail.id !== null && !disableCampaignOnStatus(install_campaign_status)">
                                                    <a class="delete" data-toggle="tooltip" data-original-title="Delete"
                                                       v-on:click="removeDetailCampign(detail.id, index)"><i
                                                        class="fa fa-trash"></i></a>
                                                </td>
                                                <td style="text-align:center;" v-if="detail.id == null && !disableCampaignOnStatus(install_campaign_status)">
                                                    <a class="delete" data-toggle="tooltip" data-original-title="Delete"
                                                       v-on:click="removeDetailCampaignFromArray(index)"><i
                                                        class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-finance" role="tabpanel"
                         aria-labelledby="nav-profile-tab">
                        <div class="card">
                            <div class="card-body">
                                <div class="array-container form-group col-md-12">
                                    <div class="col-md-12">
                                        <div class="row" style="margin-bottom: 30px;">
                                            <div class="col-md-12 alert alert-danger" v-if="finances == ''">
                                                Debe Agregar la Información Financiera
                                            </div>
                                            <div class="col-md-6">
                                                <label>Solicitud de Factura:</label>
                                                <div class="">
                                                    <input type="text" class="form-control" v-model="odf"
                                                           :disabled="this.finances_active">
                                                    <span v-if="errors_finace"
                                                          class="text-danger">{{ errors_finace[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Orden de Compra:</label>
                                                <div>
                                                    <input type="text" class="form-control" v-model="odc"
                                                           :disabled="this.finances_active">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Monto:</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" v-model="monto"
                                                           :disabled="this.finances_active">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span v-if="errors_finace"
                                                          class="text-danger">{{ errors_finace[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Notas</label>
                                                <br>
                                                <textarea style="width: 100%;" v-model="notes_finance"
                                                          :disabled="this.finances_active"/>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fecha de Inicio de Orden de Compra</label>
                                                    <br>
                                                    <date-picker v-model="date_finance_start"
                                                                 type="date|month"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fechas Final de Orden de Compra</label>
                                                    <br>
                                                    <date-picker v-model="date_finance_end"
                                                                 type="date|month" :disabled="this.finances_active"/>
                                                </div>
                                            </div>
                                            <div class="col-md-9 mt-2" v-if="this.finances_active != true">
                                                <a class="btn btn-secondary" v-on:click="addFinanceCampaign">Agregar
                                                    Información Financiera</a>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-b-0" id="">
                                        <thead>
                                        <tr>
                                            <td>
                                                Solicitud de Factura
                                            </td>
                                            <td>
                                                Orden de Compra
                                            </td>
                                            <td>
                                                Monto
                                            </td>
                                            <td>
                                                Fecha Inicio
                                            </td>
                                            <td>
                                                Fecha Final
                                            </td>
                                            <td>
                                                Acciones
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody class="table-striped" v-for="(finance, index) in finances">
                                        <tr class="array-row">
                                            <td>
                                                <p v-show="editar !== index">{{ finance.odf }}</p>
                                                <input type="text" class="input-group" v-model="finance.odf"
                                                       v-show="editar === index">
                                            </td>
                                            <td>
                                                <p v-show="editar !== index">{{ finance.odc }}</p>
                                                <input type="text" class="input-group" v-model="finance.odc"
                                                       v-show="editar === index">
                                            </td>
                                            <td>
                                                <p v-show="editar !== index">${{ finance.monto }}</p>
                                                <input type="text" class="input-group" v-model="finance.monto"
                                                       v-show="editar === index">
                                            </td>
                                            <td>
                                                <p v-show="editar !== index">{{ moment(finance.start_date).format("MM-DD-YYYY") }}</p>
                                                <date-picker v-model="finance.start_date" type="date|month" v-show="editar === index"/>
                                            </td>
                                            <td>
                                                <p v-show="editar !== index">{{ moment(finance.end_date).format("MM-DD-YYYY") }}</p>
                                                <date-picker v-model="finance.end_date" type="date|month" v-show="editar === index"/>
                                            </td>
                                            <td style="text-align:center;" v-if="finance.id !== null">
                                                <a class="edit" data-toggle="tooltip"
                                                   data-original-title="Edit" v-on:click="editFinanceCampaign(index)"><i
                                                    class="fa fa-pencil" style="cursor: pointer;"
                                                    v-show="editar !== index"/></a>
                                                <a class="save" data-toggle="tooltip"
                                                   data-original-title="Save"
                                                   v-on:click="saveNewFinanceCampaign(index)"><i
                                                    class="fa fa-save" style="cursor: pointer;"
                                                    v-show="editar === index"/></a>
                                            </td>
                                            <td style="text-align:center;" v-if="finance.id == null">
                                                <a class="edit" data-toggle="tooltip"
                                                   data-original-title="Edit" v-on:click="editFinanceCampaign(index)"><i
                                                    class="fa fa-pencil" style="cursor: pointer;"
                                                    v-show="editar !== index"/></a>
                                                <a class="save" data-toggle="tooltip"
                                                   data-original-title="Save"
                                                   v-on:click="saveNewFinanceCampaign(index)"><i
                                                    class="fa fa-save" style="cursor: pointer;"
                                                    v-show="editar === index"/></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary" type="submit" >Guardar Información Financiera</button>
                </div>
            </form>
        </div>
    </main>
</template>


<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import DatePicker from 'vue2-datepicker';
import moment from 'moment';
import 'vue2-datepicker/index.css';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Multiselect,
        DatePicker,
        Loading
    },
    props: {
        bucket: {
            type: String
        },
        campaign_status_blade: {
            type: String
        }
    },
    data() {
        return {
            isLoading: false,
            bucket_url: '',
            fullPage: true,
            campaign_id: '',
            valueType: '',
            selected_type: '',
            date: null,
            cliente: '',
            clientes: [],
            name: '',
            range: '',
            notes: '',
            requerie_desinstallion: 0,
            posible_renovation: 0,
            details: [],
            details_post: [],
            detail_id: null,
            //Table
            zonapublicitaria: '',
            zonaspublicitarias: [],
            zona: '',
            zonas: [],
            ruta: '',
            rutas: [],
            notes_details: '',
            //end table
            errors: [],
            finances_active: false,
            finances: [],
            odc: '',
            odf: '',
            monto: '',
            total_monto: '',
            comision: '',
            notes_finance: '',
            weeks: '',
            date_finance_start: '',
            date_finance_end: '',
            finance_id: null,
            errors_finace: [],
            editar: 'undefined',
            //End finance
            //Arts
            artes: [],
            files: [],
            arte_ruta: '',
            name_version: '',
            image_arte: null,
            preview: '',
            disable: false,
            errors_arts: [],
            //Contador
            contador: 0,
            campaign_status: '',
            install_campaign_status : "6"
        }
    },
    created: function () {
        this.loader();
        this.getClients();
        this.getZonasPublicitarias();
        this.getZonas();
        this.getRutas();
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
                DatePicker.current_value = response.data.date;
                this.requerie_desinstallion = response.data.requerie_desinstallion;
                this.possible_renovation = response.data.possible_renovation;
                this.notes = response.data.notes;
                this.details = response.data.details;
                this.total_monto = response.data.monto;
                this.comision = response.data.comision
                this.artes = response.data.artes.map((item) => {
                    return {
                        id : item.id,
                        name: item.name,
                        image: item.image
                    };
                });
                this.finances = response.data.finances;
                this.date = [new Date(response.data.date[0]), new Date(response.data.date[1])];
                this.onCancel();

                if (this.campaign_status == true) {
                    this.campaign_status = 'disabled'
                }
            }));
        },
        getClients: function () {
            var url = '/api/internal/campaign/clients';

            axios.get(url).then((response => {
                this.clientes = response.data
            }));
        },
        getZonasPublicitarias: function () {
            var url = '/api/internal/campaign/zonas-publicitarias';

            axios.get(url).then((response => {
                this.zonaspublicitarias = response.data
            }));
        },
        getZonas: function () {
            var url = '/api/internal/campaign/zonas';

            axios.get(url).then((response => {
                this.zonas = response.data
            }));
        },
        getRutas: function () {
            var url = '/api/internal/campaign/rutas';

            axios.get(url).then((response => {
                this.rutas = response.data
            }));
        },
        removeDetailCampign: function (id, index) {
            const url = '/api/internal/campaign/remove_details';
            this.details.splice(index, 1);
            axios.post(url, {id: id});
        },
        removeFromDetailCampaign: function (index) {
            this.details.splice(index, 1);
        },
        removeFromFinanceCampaign: function (index) {
            this.finances.splice(index, 1);
        },
        removeFinanceCampaign: function (id, index) {
            const url = '/api/internal/campaign/remove_finance';
            this.finances.splice(index, 1);
            // axios.post(url, {id: id});
        },
        addDetailCampaign: function () {
            const details_object = {
                zona: this.zona,
                ruta: this.ruta,
                zona_publicitaria: this.zonapublicitaria,
                notes: this.notes_details,
                arte: this.arte_ruta
            };
            this.details.push(details_object);
            this.zona = '';
            this.ruta = '';
            this.notes_details = '';
        },
        onImageChange(e) {
            this.image_arte = e.target.files[0];
        },
        addArtToCampaign: function () {
            if (this.name_version && this.image_arte) {
                this.contador = this.contador + 1;
                let objet = URL.createObjectURL(this.image_arte);
                const arts_object = {
                    name: this.name_version,
                    on_camping_id: this.contador + 1,
                    image: this.image_arte,
                    preview: objet
                };
                this.artes.push(arts_object);
                this.image_arte = '';
                this.name_version = '';
            } else if (!this.name_version || !this.image_arte) {
                this.errors_arts.push('Estos campos son requeridos')
            }
        },
        validateForm() {
            if (!this.cliente) {
                this.errors = {client: 'Debes Seleleccionar un Cliente'};
                return false;
            } else if (!this.date) {
                this.errors = {date: 'Debes Seleleccionar una Fecha'};
                return false;
            } else if (this.artes == '') {
                this.errors = {artes: true};
                return false;
            } else if (this.details == '') {
                this.errors = {details: true};
                return false;
            }

            return true;
        },
        addFinanceCampaign: function (e) {
            e.preventDefault();
            if (this.comision && this.monto && this.odf) {
                const finance_array = {
                    id: this.finance_id,
                    odc: this.odc,
                    odf: this.odf,
                    monto: this.monto,
                    start_date: this.date_finance_start,
                    end_date: this.date_finance_end,
                    notes: this.notes_finance,
                };
                this.finances.push(finance_array);
                this.odc = '';
                this.odf = '';
                this.monto = '';
                this.errors_finance = '';
                this.date_finance_end = '';
                this.date_finance_start = '';
                this.notes_finance = '';
                this.there_comision = true;
            } else if (!this.odf || !this.monto) {
                this.errors_finace.push('Estos campos son requeridos')
            }
        },
        editFinanceCampaign: function (index,) {
            this.editar = index;
            this.editar_finance = index;
        },
        saveNewFinanceCampaign: function () {
            this.editar = 'false';
        },
        updateCampaingRequest: function (e) {
            e.preventDefault();

            if (!this.validateForm()) {
                return false;
            }

            const config = {
                headers: {'content-type': 'multipart/form-data'}
            };

            let formData = new FormData();

            //Preparando imagenes para enviar
            for (let i = 0; i < this.artes.length; i++) {
                let file = this.artes[i].image;
                if (this.artes[i].on_camping_id == null) {
                    formData.append('files[' + this.artes[i].name + ']', file);
                } else {
                    formData.append('files[' + this.artes[i].on_camping_id + ']', file);
                }
            }

            for (let i = 0; i < this.artes.length; i++) {
                this.artes[i].image = '';
            }
            const data = {
                name: this.name,
                client: this.cliente.id,
                notes: this.notes,
                requerie_desinstallion: this.requerie_desinstallion,
                posible_renovation: this.posible_renovation,
                details: this.details,
                date: this.date,
                campaign_id: this.campaign_id,
                monto: this.total_monto,
                comision: this.comision,
                finances: this.finances,
                artes: this.artes,
            };

            formData.append('data', JSON.stringify(data));

            const url = '/api/internal/campaign/update';

            this.loader();
            axios.post(url, formData).then(function (response) {
                window.location = '/campañas';
            }).catch(e => {
                this.onCancel()
                if (e.response.status === 422) {
                    this.errors = e.response.data.error;
                }
                new Noty({
                    title: "Rutas de Autobuseros",
                    text: e.response.data.error,
                    type: "error"
                }).show();
            });
        },
        loader: function () {
            this.isLoading = true;
        },

        onCancel: function () {
            this.isLoading = false;
        },

        // TODO: We would like to disable the campaign edition after the status 2 ?
        disableCampaignOnStatus: function (status){
            return this.campaign_status_blade >= 6;
        }
    }
}
</script>

<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
.alert-danger {
    color: black;
    background-color: #cd201f57;
    border-color: #bd1d1d;
}
</style>
