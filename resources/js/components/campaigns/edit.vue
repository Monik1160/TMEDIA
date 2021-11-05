<template>
    <main>
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"></loading>
        <form method="put" enctype="multipart/form-data" @submit="updateCampaingRequest">
            <div class="container-fluid animated fadeIn">
                <div class="row">
                    <div class="col-md-12 bold-labels">
                        <!-- Default box -->
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
                                                             :allow-empty="false" required :disabled="this.status"/>
                                                <span v-if="errors.client"
                                                      class="error text-danger">{{ errors.client[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Nombre de la campaña:</label>
                                            <input type="text" name="name" value="" autocomplete="off"
                                                   class="form-control" v-model="name" required :disabled="this.status">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Fechas de inicio y Finalizacion de la Campaña</label>
                                            <br>
                                            <date-picker v-model="date" range type="date|month"/>
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
                                                       id="requerie_desinstallion_1" name="requerie_desinstallion"
                                                       value="0" checked="" v-model="requerie_desinstallion"
                                                       :disabled="this.status">
                                                <label class="radio-inline form-check-label font-weight-normal"
                                                       for="requerie_desinstallion_1">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input"
                                                       id="requerie_desinstallion_2" name="requerie_desinstallion"
                                                       value="1" v-model="requerie_desinstallion"
                                                       :disabled="this.status">
                                                <label class="radio-inline form-check-label font-weight-normal"
                                                       for="requerie_desinstallion_2">Si</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div>
                                                <label>Posible Renovación?</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input"
                                                       id="requerie_desinstallion_1" name="posible_renovation"
                                                       value="0" checked="" v-model="posible_renovation"
                                                       :disabled="this.status">
                                                <label class="radio-inline form-check-label font-weight-normal"
                                                       for="requerie_desinstallion_1">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input"
                                                       id="requerie_desinstallion_2" name="posible_renovation"
                                                       value="1" v-model="posible_renovation" :disabled="this.status">
                                                <label class="radio-inline form-check-label font-weight-normal"
                                                       for="requerie_desinstallion_2">Si</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Monto de la Campaña:</label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control" v-model="total_monto"
                                                       required :disabled="this.status">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Comisión:</label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control"
                                                       v-model="comision" required :disabled="this.status">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Notas</label>
                                            <textarea name="notes" placeholder="Notas internas"
                                                      class="form-control" v-model="notes" :disabled="this.status"/>
                                            <span v-if="errors.notes"
                                                  class="error text-danger">{{ errors.notes[0] }}</span>
                                        </div><!-- load the view from type and view_namespace attribute if set -->
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
                            <div class="col-md-12">
                                <div class="row" style="margin-bottom: 30px;">
                                    <div class="col-md-12 alert alert-danger" v-if="errors.artes">
                                        Debes Agregar Arte a la Campaña
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nombre:</label>
                                        <input type="text" name="name_version" value="" autocomplete="off"
                                               class="form-control" v-model="name_version" :disabled="this.status">
                                        <span v-if="errors_arts"
                                              class="text-danger">{{ errors_arts[0] }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Imagen:</label>
                                        <input type="file" class="form-control" v-on:change="onImageChange"
                                               :disabled="this.status">
                                        <span v-if="errors_arts"
                                              class="text-danger">{{ errors_arts[0] }}</span>
                                    </div>
                                    <div class="col-md-12 mt-4" v-if="this.status == false">
                                        <a class="btn btn-secondary" v-on:click="addArtToCampaign">Agregar Arte
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <table v-show="artes.length > 0" class="table table-bordered table-striped m-b-0"
                                   id="artes">
                                <thead>
                                <tr>
                                    <td>
                                        Nombre
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
                            <div class="col-md-12">
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
                                        <label>Zona Publicitaria:</label>
                                        <br>
                                        <multiselect v-model="zonapublicitaria"
                                                     deselect-label="Can't remove this value"
                                                     track-by="name" label="name"
                                                     placeholder="Seleccione Zona Publicitaria"
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
                                        <a class="btn btn-secondary" v-on:click="addDetailCampaign">Agregar Detalle
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <table v-show="details.length > 0" class="table table-bordered table-striped m-b-0"
                                   id="artes">
                                <thead>
                                <tr>
                                    <td>
                                        Ruta
                                    </td>
                                    <td>
                                        Zonas Publicitaria
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
                                        {{ detail.ruta.name }}
                                        <!--                                        {{ detail.zona.name}}-->
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
                                    <td style="text-align:center;" v-if="detail.id !== null">
                                        <a class="delete" data-toggle="tooltip" data-original-title="Delete"
                                           v-on:click="removeDetailCampign(detail.id, index)"><i
                                            class="fa fa-trash"></i></a>
                                    </td>
                                    <td style="text-align:center;" v-if="detail.id == null">
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
                <div class="form-group col-sm-12">
                    <button class="btn btn-primary" type="submit">Actualizar Solicitud de Campaña</button>
                </div>

            </div>
        </form>
    </main>
</template>

<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import UploadImage from 'vue-upload-image';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Multiselect,
        DatePicker,
        UploadImage,
        Loading
    },
    data() {
        return {
            campaign_id: '',
            valueType: '',
            selected_type: '',
            date: null,
            cliente: '',
            clientes: [],
            name: '',
            range: '',
            notes: '',
            status: '',
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
            //Finance
            comision: '',
            total_monto: '',
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
            //Button
            isLoading: false,
            fullPage: true,
            //Disable
            disable_form: false,
        }
    },
    created: function () {
        this.loader()
        this.getClients();
        this.getZonasPublicitarias();
        this.getZonas();
        this.getRutas();
        this.getCampaigns();
    },
    methods: {
        loader: function () {
            this.isLoading = true;
        },

        onCancel() {
            this.isLoading = false;
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
                this.onCancel();
                this.campaign_id = response.data.campaign_id;
                this.cliente = response.data.client;
                this.status = response.data.status;
                this.name = response.data.name;
                DatePicker.current_value = response.data.date;
                this.requerie_desinstallion = response.data.requerie_desinstallion;
                this.possible_renovation = response.data.possible_renovation;
                this.notes = response.data.notes;
                this.details = response.data.details;
                this.total_monto = response.data.monto;
                this.comision = response.data.comision;
                this.artes = response.data.artes;
                this.date = [new Date(response.data.date[0]), new Date(response.data.date[1])];
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
        removeDetailCampaignFromArray: function (index) {
            this.details.splice(index, 1);
        },
        addDetailCampaign: function () {
            if (this.ruta == '' || this.zonapublicitaria == '' || this.notes_details == '' || this.arte_ruta == '') {
                this.errors = {details: true};
            } else {
                this.errors = {details: false};
                const details_object = {
                    zona: this.zona,
                    ruta: this.ruta,
                    zona_publicitaria: this.zonapublicitaria,
                    notes: this.notes_details,
                    arte: this.arte_ruta
                };
                console.log(details_object)
                this.details.push(details_object);
                this.zona = '';
                this.ruta = '';
                this.notes_details = '';
            }
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
        updateCampaingRequest: function (e) {
            e.preventDefault();
            this.loader();

            if (!this.validateForm()) {
                this.onCancel();
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
                artes: this.artes,
            };


            formData.append('data', JSON.stringify(data));

            const url = '/api/internal/campaign/update';

            axios.post(url, formData, config).then(function (response) {
                window.location = '/campañas';
            }).catch(e => {
                if (e.response.status === 422) {
                    this.errors = e.response.data.error;
                    this.onCancel()
                }
            });
        },
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
