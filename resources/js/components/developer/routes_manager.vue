<template>
    <main>
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
                                            <h3>Rutas Autobuseros</h3>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Seleccione Autobusero:</label>
                                            <multiselect v-model="autobusero"
                                                         deselect-label="Can't remove this value"
                                                         track-by="name" label="name"
                                                         placeholder="Seleccione Cliente"
                                                         :options="this.autobuseros" :searchable="true"
                                                         @select="getRoutes"
                                                         :allow-empty="false" required/>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <div v-if="this.routes != ''">
                                                <label>Seleccione las rutas del autobusero: </label>
                                                <v-multiselect-listbox :options="this.routes"
                                                                       v-model="selectedStates"
                                                                       :reduce-display-property="(option) => option.label"
                                                                       :reduce-value-property="(option) => option.code"
                                                                       search-options-placeholder="Buscar Rutas"
                                                                       selected-options-placeholder="Buscar Rutas Seleccionadas"
                                                ></v-multiselect-listbox>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <button class="btn btn-primary" type="submit" @click="saveAutobuserosRutas" :disabled="disabled_button">Guardar Rutas de
                            Autobuseros
                        </button>
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


export default {
    components: {
        Multiselect,
        vMultiselectListbox,
        Loading
    },
    data() {
        return {
            autobusero: '',
            autobuseros: [],
            routes: [],
            selectedStates: [],
            isLoading: false,
            fullPage: true,
            disabled_button: true,
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

        getAutobuseros: function () {
            var url = '/api/internal/campaign/autobuseros_all';
            this.routes = '';
            this.selectedStates = '';

            axios.get(url).then((response => {
                this.autobuseros = response.data
                this.onCancel();
            }));
        },

        getRoutes: function (autobusero, id) {
            this.loader();
            var url = `/api/internal/campaign/ruta_all/${autobusero.id}`;

            axios.get(url).then((response => {
                this.routes = response.data.routes_all
                this.selectedStates = response.data.routes_selected
                this.disabled_button = false
                this.onCancel();
            }));
        },
        saveAutobuserosRutas: function (e) {
            e.preventDefault();

            let params = {
                'autobusero_id': this.autobusero.id,
                'rutas_selected': this.selectedStates
            }

            this.loader();

            let url = '/api/internal/campaign/save_rutas_autobuseros';

            axios.post(url, params)
                .then((response => {
                new Noty({
                    title: "Rutas de Autobuseros",
                    text: "Rutas de Autobuseros Actualizas y Creadas",
                    type: "success"
                }).show();
                this.onCancel();
                }))
                .catch(error => {
                    new Noty({
                        title: "Rutas de Autobuseros",
                        text: error.response.data.error,
                        type: "error"
                    }).show();
                    this.onCancel();
                    this.routes = '';
                    this.autobusero = '';
                    this.selectedStates = '';
                    this.disabled_button = true;
                })
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
</style>
