<template>
    <main>
        <loading :active.sync="isLoading"
                 :is-full-page="fullPage"
                 :on-cancel="onCancel"
        ></loading>
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Información de la Campaña
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div>
                                        <label>Cliente:</label> <strong>{{ task.company_name }}</strong>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Nombre de la campaña:</label> <strong>{{ task.campaing_name }}</strong>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Notas:</label>
                                    <p>{{ task.campaing_notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Progreso
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Instalador Asignado:</label>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <img :src="task.image_profile_installer" style="width: 16em;"/><br>
                                            <strong>{{ task.installer }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Estado de la Tarea:</label>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <strong>{{ task.status }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Bus:</label>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <strong>{{ task.bus_plate }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Zonas de Instalación:</label>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <main v-for="zonas in task.zonas_instalacion">
                                                <strong>{{ zonas }}</strong>
                                            </main>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Notas:</label>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <main v-if="task.notes != ''">
                                                <strong>{{ task.notes }}</strong>
                                            </main>
                                            <main v-else>
                                                No hay notas
                                            </main>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--                            <div class="col-md-6">-->
                            <!--                                <div class="form-group col-sm-12">-->
                            <!--                                    <label>Imagenes Subidas por el Instalador: </label>-->
                            <!--                                    <div class="col-md-12">-->
                            <!--                                        <main v-if="task.images != ''">-->
                            <!--                                            <carousel :scrollPerPage="true" :perPageCustom="[[768, 1], [1024, 1]]">-->
                            <!--                                                <slide v-for="foto in task.images">-->
                            <!--                                                    <img :src="foto">-->
                            <!--                                                </slide>-->
                            <!--                                            </carousel>-->
                            <!--                                        </main>-->
                            <!--                                        <main v-else>-->
                            <!--                                            El instalador no ha subir imagenes todavia-->
                            <!--                                        </main>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Secciones
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped m-b-0" id="sections_table">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Receiving</th>
                                <th scope="col">Instalation Image</th>
                                <th scope="col">Unistallation Image</th>
                            </tr>
                            </thead>
                            <tbody class="table-striped" v-for="section in task.sections">
                            <tr style="text-align:center;" class="array-row image_table_task">
                                <td v-if="section.name == 'back'">Trasera</td>
                                <td v-if="section.name == 'right_side'">Lateral Derecho</td>
                                <td v-if="section.name == 'left_side'">Lateral Izquierdo</td>
                                <td v-if="section.name == 'interior'">Interior</td>
                                <td v-for="image in section.images">
                                    <template v-if="image.image != null">
                                        <img :src="image.image">
                                    </template>
                                    <template v-else>
                                        No hay Foto
                                    </template>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <label><input type="checkbox" class="approved_photos_check" value="first_checkbox"
                                      @change="approvedPhotoToReports"> Aprobar fotos para
                            Reporte</label>
                        <div v-if="task.approved == null && task.status == 'Finalizada' && visible == false">
                            <div class="button_save">
                                <a href="" class="btn btn-primary" v-on:click.prevent="addDeclineMessage('approved')">Aprobar</a>
                                <a style="color:white;" v-on:click="visible = true" class="btn btn-danger">Rechazar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="task.approved == null && task.status == 'Finalizada' && visible == true">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Razón de Rechazo de Imagen:
                    </div>
                    <div class="card-body">
                        <label>Motivo del rechazo de las imagenes</label>
                        <div class="form-group">
                            <textarea class="form-control" id="dcline_message_task" name="decline_message"
                                      rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <a class="btn btn-danger" v-on:click.prevent="addDeclineMessage('decline')">Enviar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <label style="font-weight: bolder;">Comentarios:</label>
                    </div>
                    <div class="card-body">
                        <main v-if="task.comments != ''">
                            <div class="card" v-for="comment in task.comments">
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                        <p>{{ comment.body }}</p>
                                        <footer class="blockquote-footer">By: <cite
                                            title="Source Title">{{ comment.user }}</cite>
                                        </footer>
                                    </blockquote>
                                </div>
                            </div>
                        </main>
                        <main v-else>
                            No hay comentarios en la tarea
                        </main>
                    </div>
                </div>
            </div>
        </div>
        <div class="button_save">
            <a href="/tarea" class="btn btn-primary">Volver</a>
        </div>
    </main>
</template>


<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import moment from 'moment';
import {Carousel, Slide} from 'vue-carousel';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';


export default {
    components: {
        Multiselect,
        Carousel,
        Slide,
        Loading
    },
    props: {
        task_id: {
            type: String
        }
    },
    data() {
        return {
            isLoading: false,
            fullPage: true,
            task: {},
            visible: false,
        }
    },
    created: function () {
        this.loader();
        this.getTaskDetail();
    },
    methods: {
        moment: function (d) {
            return moment(d);
        },

        getTaskDetail: function () {
            let url = '/api/internal/task/details';
            let id = document.getElementById('tarea_id').value;
            let approved_checkbox = document.getElementsByClassName('approved_photos_check');

            axios.post(url, {
                id: id,
            }).then((response => {
                this.task = response.data.data;
                if (this.task.is_picked_up == 1) {
                    approved_checkbox[0].checked = true;
                } else {
                    approved_checkbox[0].checked = false;
                }
                this.onCancel();
            }));
        },

        loader: function () {
            this.isLoading = true;
        },

        onCancel: function () {
            this.isLoading = false;
        },

        approvedPhotoToReports: function () {
            let id = document.getElementById('tarea_id').value;
            let status_check = document.getElementsByClassName('approved_photos_check')
            let approved = '';

            if (status_check[0].checked) {
                approved = 1
            } else {
                approved = 0
            }

            let url = '/api/internal/task/approved-photos-to-reports';

            axios.post(url, {
                task_id: id,
                photos_approved: approved
            }).then((response => {
                new Noty({
                    title: "Tarea Aprovada",
                    text: "Tarea Aprovada Exitosamente",
                    type: "success"
                }).show();
                // this.getTaskDetail();
            }))
        },

        addDeclineMessage: function (status) {
            this.loader();

            this.visible = false;

            let url = '/api/internal/task/decline-aprroved';

            let decline_message_text = (document.getElementById('dcline_message_task')) ? document.getElementById('dcline_message_task').value : '';
            let id = document.getElementById('tarea_id').value;

            let data = {
                task_id: id,
                body: decline_message_text,
                task_status: status
            }

            axios.post(url, data).then((response => {
                new Noty({
                    title: "Tarea Rechazada",
                    text: "Tarea Rechazada y enviada al instalados correctamente",
                    type: "success"
                }).show();
                this.getTaskDetail();
            }))

        }
    }
}
</script>

<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
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

.image_table_task img{
    width: 200px;
}
</style>
