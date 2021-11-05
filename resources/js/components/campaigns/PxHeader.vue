<template>
    <div>
        <div class="tab-container  mb-2">
            <div class="tab-content p-0 ">
                <div role="tabpanel" class="tab-pane  active" id="tab_general">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <div>
                                <label>Cliente:</label>
                                <p>{{ cliente.name }}</p>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Nombre de la campaña:</label>
                            <p>{{ name }}</p>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Fechas de iniciode la Campaña</label>
                            <p>{{ date[0] }}</p>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Fechas de Finalizacion de la Campaña</label>
                            <p>{{ date[1] }}</p>
                        </div>
                        <div class="form-group col-sm-6">
                            <div>
                                <label>Requiere desinstalación inmediata?</label>
                                <p>{{ requerie_desinstallion }}</p>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div>
                                <label>Posible Renovación?</label>
                                <p>{{ possible_renovation }}</p>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Notas</label>
                            <p>{{ notes }}</p>
                        </div>
                        <!-- load the view from type and view_namespace attribute if set -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";

export default {
    name: "PxHeader",
    data() {
        return {
            campaign_id: '',
            date: [],
            cliente: '',
            clientes: [],
            name: '',
            notes: '',
            requerie_desinstallion: '',
            possible_renovation: '',
            details: [],
            artes: [],
            bus: '',
            buses: [],
            detail_id: '',
            //Modal Items
            autobuseros: [],
            autobusero: '',
            zonas_publicitaria: [],
            route_id: '',
            opened: [],
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
                this.requerie_desinstallion = response.data.requerie_desinstallion;
                this.possible_renovation = response.data.possible_renovation;
                this.notes = response.data.notes;
                this.details = response.data.details;
                this.total_monto = response.data.monto;
                this.comision = response.data.comision;
                this.artes = response.data.artes;
                this.finances = response.data.finances;
                this.date = [new Date(response.data.date[0]), new Date(response.data.date[1])];
            }));
        },
    }
}
</script>

<style scoped>
label{
    font-weight: bold;
}
</style>
