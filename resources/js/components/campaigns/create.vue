<template>
    <main>
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"></loading>
        <form method="post" enctype="multipart/form-data" @submit="createCampaingRequest">
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
                                                             :allow-empty="false" required/>
                                                <span v-if="errors.client"
                                                      class="error text-danger">{{ errors.client }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Nombre de la campaña:</label>
                                            <input type="text" name="name" value="" autocomplete="off"
                                                   class="form-control" v-model="name" required>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Fechas de inicio y Finalizacion de la Campaña</label>
                                            <br>
                                            <date-picker v-model="date" range></date-picker>
                                            <br>
                                            <span v-if="errors.date" class="error text-danger">{{ errors.date }}</span>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div>
                                                <label>Requiere desinstalación inmediata?</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input"
                                                       id="requerie_desinstallion_1" name="requerie_desinstallion"
                                                       value="0" checked="" v-model="requerie_desinstallion">
                                                <label class="radio-inline form-check-label font-weight-normal"
                                                       for="requerie_desinstallion_1">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input"
                                                       id="requerie_desinstallion_2" name="requerie_desinstallion"
                                                       value="1" v-model="requerie_desinstallion">
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
                                                       value="0" checked="" v-model="posible_renovation">
                                                <label class="radio-inline form-check-label font-weight-normal"
                                                       for="requerie_desinstallion_1">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input"
                                                       id="requerie_desinstallion_2" name="posible_renovation"
                                                       value="1" v-model="posible_renovation">
                                                <label class="radio-inline form-check-label font-weight-normal"
                                                       for="requerie_desinstallion_2">Si</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Monto de la Campaña:</label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control" v-model="total_monto"
                                                       required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Comisión:</label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control"
                                                       v-model="comision" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Notas</label>
                                            <textarea name="notes" placeholder="Notas internas"
                                                      class="form-control" v-model="notes"/>
                                            <span v-if="errors.notes"
                                                  class="error text-danger">{{ errors.notes }}</span>
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
                                    <td>
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
                                        Ruta o Zona
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
                                        Actions
                                    </td>
                                </tr>
                                </thead>
                                <tbody class="table-striped">
                                <tr class="array-row" id="arte_1" v-for="(detail, key) in details">
                                    <td>
                                        {{ detail.ruta.name }}
                                        {{ detail.zona.name }}
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
                                    <td>
                                        <a v-on:click="removeDetailTask(key)">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-12">
                    <button class="btn btn-primary" type="submit">Crear Solicitud de Campaña</button>
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
        const valueType = {
            value2date: value => {
                return value ? moment(new Date(value), "YYYY-MM-DD").toDate() : null;
            },
            date2value: date => {
                return date ? moment(date).format("YYYY-MM-DD") : null;
            }
        };
        return {
            isLoading: false,
            fullPage: true,
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
            submitSend: '',
        }
    },
    created: function () {
        this.loader()
        this.getClients();
        this.getZonasPublicitarias();
        this.getZonas();
        this.getRutas();

    },
    methods: {
        loader: function () {
            this.isLoading = true;
        },

        onCancel() {
            this.isLoading = false;
        },

        getClients: function () {
            var url = '/api/internal/campaign/clients';

            axios.get(url).then((response => {
                this.clientes = response.data
                this.onCancel();
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
        createCampaingRequest: function (e) {
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
            let files = [];
            for (let i = 0; i < this.artes.length; i++) {
                let file = this.artes[i].image;
                formData.append('files[' + this.artes[i].on_camping_id + ']', file);
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
                comision: this.comision,
                monto: this.total_monto,
                artes: this.artes,
            };


            formData.append('data', JSON.stringify(data));

            const url = '/api/internal/campaign/create';

            axios.post(url, formData, config).then(function (response) {
                window.location = '/campañas';
                this.onCancel()
            }).catch(e => {
                if (e.response.status === 422) {
                    this.errors = e.response.data.error;
                    this.onCancel()
                }
            });
        },

        removeDetailTask: function (key) {
            this.details.splice(key, 1);
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
