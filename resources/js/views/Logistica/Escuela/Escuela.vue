<template>
    <div>
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">configuración</h1>
            <b class="badge bg-dark text-white ms-2">
                C B T
            </b>
        </div>
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Datos de mi escuela</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" form="direccion">DIRECCIÓN</label>
                                <input v-model="escuela.direccion" name="direccion" type="text" class="form-control"
                                    placeholder="DIRECCIÓN" />
                                <div v-if="errores.direccion" class="form-text text-danger">
                                    {{ errores.direccion[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" form="departamento">DEPARTAMENTO</label>
                                <input v-model="escuela.departamento" name="departamento" type="text" class="form-control"
                                    placeholder="Correo electronico" />
                                <div v-if="errores.departamento" class="form-text text-danger">
                                    {{ errores.departamento[0] }}
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="nombre_escuela">NOMBRE DE LA ESCUELA</label>
                                <input v-model="escuela.nombre_escuela" type="text" class="form-control"
                                    id="nombre_escuela" placeholder="NOMBRE DE LA ESCUELA">
                                <div v-if="errores.nombre_escuela" class="form-text text-danger">
                                    {{ errores.nombre_escuela[0] }}
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="turno">TURNO</label>
                                <input v-model="escuela.turno" type="text" class="form-control" id="turno"
                                    placeholder="Turno">
                                <div v-if="errores.turno" class="form-text text-danger">
                                    {{ errores.turno[0] }}
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="nivel">NIVEL</label>
                                <input v-model="escuela.nivel" type="text" class="form-control" id="nivel"
                                    placeholder="Nivel">
                                <div v-if="errores.nivel" class="form-text text-danger">
                                    {{ errores.nivel[0] }}
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="clave_trabajo">CLAVE CENTRO DE TRABAJO</label>
                                <input v-model="escuela.clave_trabajo" type="text" class="form-control" id="clave_trabajo"
                                    placeholder=">CLAVE CENTRO DE TRABAJO">
                                <div v-if="errores.clave_trabajo" class="form-text text-danger">
                                    {{ errores.clave_trabajo[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="numero_cct">No. DE C.RE.S.E.</label>
                                <input v-model="escuela.numero_cct" type="text" class="form-control" id="numero_cct"
                                    placeholder="No. DE C.RE.S.E.">
                                <div v-if="errores.numero_cct" class="form-text text-danger">
                                    {{ errores.numero_cct[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="zona_escolar">ZONA ESCOLAR</label>
                                <input v-model="escuela.zona_escolar" type="text" class="form-control" id="zona_escolar"
                                    placeholder="ZONA ESCOLAR">
                                <div v-if="errores.zona_escolar" class="form-text text-danger">
                                    {{ errores.zona_escolar[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="domicilio">DOMICILIO</label>
                                <input v-model="escuela.domicilio" type="text" class="form-control" id="domicilio"
                                    placeholder="DOMICILIO">
                                <div v-if="errores.domicilio" class="form-text text-danger">
                                    {{ errores.domicilio[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="localidad_colonia">LOCALIDAD O COLONIA</label>
                                <input v-model="escuela.localidad_colonia" type="text" class="form-control" id="localidad_colonia"
                                    placeholder=">LOCALIDAD O COLONIA">
                                <div v-if="errores.localidad_colonia" class="form-text text-danger">
                                    {{ errores.localidad_colonia[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="municipio">MUNICIPIO</label>
                                <input v-model="escuela.municipio" type="text" class="form-control" id="municipio"
                                    placeholder="MUNICIPIO">
                                <div v-if="errores.municipio" class="form-text text-danger">
                                    {{ errores.municipio[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="telefono">TELÉFONO</label>
                                <input v-model="escuela.telefono" type="text" class="form-control" id="telefono"
                                    placeholder="TELÉFONO" @input="validarTelefono">
                                <div v-if="errores.telefono" class="form-text text-danger">
                                    {{ errores.telefono[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="docente">DOCENTE</label>
                                <input v-model="escuela.docente" type="text" class="form-control" id="docente"
                                    placeholder="DOCENTE">
                                <div v-if="errores.docente" class="form-text text-danger">
                                    {{ errores.docente[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="subdirector_escolar">SUBDIRECTORA ESCOLAR</label>
                                <input v-model="escuela.subdirector_escolar" type="text" class="form-control" id="subdirector_escolar"
                                    placeholder="SUBDIRECTORA ESCOLAR">
                                <div v-if="errores.subdirector_escolar" class="form-text text-danger">
                                    {{ errores.subdirector_escolar[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="director_escolar">DIRECTOR ESCOLAR</label>
                                <input v-model="escuela.director_escolar" type="text" class="form-control" id="director_escolar"
                                    placeholder="DIRECTOR ESCOLAR">
                                <div v-if="errores.director_escolar" class="form-text text-danger">
                                    {{ errores.director_escolar[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="secretario_escolar">SECRETARIO ESCOLAR</label>
                                <input v-model="escuela.secretario_escolar" type="text" class="form-control" id="secretario_escolar"
                                    placeholder="SECRETARIO ESCOLAR">
                                <div v-if="errores.secretario_escolar" class="form-text text-danger">
                                    {{ errores.secretario_escolar[0] }}
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 text-end">
                                <button @click="guardar" class="btn btn-primary" v-if="hasPermission('puede editar datos de mi escuela')">
                                    <i class="bi bi-floppy2"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import api from '../../../services/api';
import { mapGetters, mapActions } from 'vuex';
import _ from 'lodash';

export default {
    name: 'Escuela',
    data() {
        return {
            escuela:{
                nombre_escuela:null,
                direccion:null,
                departamento:null,
                turno:null,
                nivel:null,
                clave_trabajo:null,
                numero_cct:null,
                zona_escolar:null,
                domicilio:null,
                localidad_colonia:null,
                municipio:null,
                telefono:null,
                docente:null,
                subdirector_escolar:null,
                director_escolar:null,
                secretario_escolar:null,
            },
            errores: {},
        };
    },
    computed: {
        ...mapGetters('auth', ['hasPermission']),
    },
    methods: {
        async consultar() {
            this.cargando = true;
            try {
                const res = await api.get('/gestion/escuela');
                this.escuela = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        async guardar() {
            try {
                const filtered = _.pick(this.escuela, ['nombre_escuela','direccion','departamento','turno','nivel','clave_trabajo','numero_cct','zona_escolar','domicilio','localidad_colonia','municipio','telefono','docente','subdirector_escolar','director_escolar','secretario_escolar']);
                const res = await api.put('/gestion/escuela', filtered);
                this.errores = {}; // 🔄 Limpia los errores
                this.escuela = res.data.Escuela;
                this.$swal.fire('Éxito', '✅ Se actualizó la información correctamente', 'success');
            } catch (error) {
                if (error.response && error.response.status == 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', '❌ No se actualizó la información.', 'error');
                }
            }
        },
        validarTelefono() {
            const telefono = this.user.telefono;
            const erroresTelefono = [];

            const soloNumeros = /^[0-9]*$/;
            if (!soloNumeros.test(telefono.toString())) {
                this.user.telefono = this.user.telefono.replace(/\D/g, '');
                erroresTelefono.push('El teléfono solo debe contener números.');
                // Elimina todo lo que no sea número
            }
            if (telefono.toString().length > 10) {
                this.user.telefono = this.user.telefono.slice(0, 10);
                erroresTelefono.push('El teléfono no debe exceder los 10 dígitos.');
            }

            this.errores.telefono = erroresTelefono.length ? erroresTelefono : [];
        },

    },
    mounted() {
        this.consultar();
    }
};
</script>