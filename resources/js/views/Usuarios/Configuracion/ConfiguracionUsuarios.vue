<template>
    <div>
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Perfil</h1>
            <b class="badge bg-dark text-white ms-2">
                {{ role?.name || user?.rol || 'Usuario' }}
            </b>
        </div>
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detalles del perfil</h5>
                    </div>
                    <div class="card-body text-center">
                        <img :src="avatar_url" :alt="user.name" class="img-fluid rounded-circle mb-2" width="128"
                            height="128" />

                        <h5 class="card-title mb-0">{{ user?.name }}</h5>
                        <div class="text-muted mb-2">{{ role?.name || user?.rol || 'Usuario' }}</div>
                        <div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person-bounding-box"></i> Actualizar Imagen
                                </label>
                                <input class="form-control" type="file" accept="image/*" @change="handleFileChange" />

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-8 col-xl-9">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mis datos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" form="name">Nombre Completo</label>
                                <input v-model="user.name" name="name" type="text" class="form-control"
                                    placeholder="Nombre Completo" />
                                <div v-if="errores.name" class="form-text text-danger">
                                    {{ errores.name[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" form="email">Email</label>
                                <input v-model="user.email" name="email" type="email" class="form-control"
                                    placeholder="Correo electronico" />
                                <div v-if="errores.email" class="form-text text-danger">
                                    {{ errores.email[0] }}
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="telefono">Teléfono {{ user.telefono }}</label>
                                <input v-model="user.telefono" type="text" class="form-control" id="telefono"
                                    placeholder="Telefono" @input="validarTelefono">
                                <div v-if="errores.telefono" class="form-text text-danger">
                                    {{ errores.telefono[0] }}
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="domicilio">Domicilio</label>
                                <input v-model="user.domicilio" type="text" class="form-control" id="domicilio"
                                    placeholder="Domicilio">
                                <div v-if="errores.domicilio" class="form-text text-danger">
                                    {{ errores.domicilio[0] }}
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="localidadColonia">Localidad o Colonia</label>
                                <input v-model="user.localidadColonia" type="text" class="form-control"
                                    id="Localidad o Colonia" placeholder="localidadColonia">
                                <div v-if="errores.localidadColonia" class="form-text text-danger">
                                    {{ errores.localidadColonia[0] }}
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" form="password_hold">Contraseña anterior</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" @click="showPasswordHold = !showPasswordHold">
                                        <i :class="showPasswordHold ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
                                    </span>
                                    <input v-model="user.password_hold" name="password_hold"
                                        :type="showPasswordHold ? 'text' : 'password'" class="form-control"
                                        placeholder="Contraseña anterior" />
                                </div>
                                <div v-if="errores.password_hold || errores.password || errores.password_confirmation"
                                    class="form-text text-danger">
                                    {{ errores.password_hold?.[0] || errores.password?.[0] ||
                                        errores.password_confirmation?.[0] }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" form="password">Contraseña nueba</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" @click="showPassword = !showPassword">
                                        <i :class="showPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
                                    </span>
                                    <input v-model="user.password" name="password"
                                        :type="showPassword ? 'text' : 'password'" class="form-control"
                                        placeholder="Contraseña" />
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" form="password_confirmation">
                                    confirmación
                                </label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" @click="showPasswordConf = !showPasswordConf">
                                        <i :class="showPasswordConf ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
                                    </span>
                                    <input v-model="user.password_confirmation" name="password_confirmation"
                                        :type="showPasswordConf ? 'text' : 'password'" class="form-control"
                                        placeholder="confirmación de contraseña" />
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 text-end">
                                <button @click="guardar" class="btn btn-primary">
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
    name: 'ConfiguracionUsuarios',
    data() {
        return {
            user: {
                id: null,
                name: '',
                email: '',
                estatus: '',
                telefono: '',
                domicilio: '',
                localidadColonia: '',
                password_hold: '',
                password: '',
                password_confirmation: ''

            },
            avatar_url: null,
            role: {
                name: '',
                permissions: []
            },
            selectedFile: null,
            preview: null,
            errores: {},
            showPasswordHold: false, // 👁️ para alternar visibilidad
            showPassword: false, // 👁️ para alternar visibilidad
            showPasswordConf: false,
        };
    },
    computed: {
        ...mapGetters('auth', ['getUser']),
    },
    watch: {
        getUser: {
            handler(newVal) {
                if (newVal) {
                    this.avatar_url = newVal.avatar_url;
                    this.role = { ...newVal.role };
                    newVal = newVal?.user ?? newVal;
                    this.user = {
                        ...newVal,
                        password_hold: '',
                        password: '',
                        password_confirmation: ''
                    };
                }
            },
            immediate: true
        }
    },
    methods: {
        ...mapActions(['toggleCollapsed']),
        collapseMenu() {
            this.toggleCollapsed();
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            // console.log(file);
            if (file && file.type.startsWith("image/")) {
                this.selectedFile = file;
                this.preview = URL.createObjectURL(file);
                this.uploadImage();
            } else {
                this.$swal.fire('Error', '❌ Solo se permiten archivos de imagen.', 'error');
            }
        },
        async uploadImage() {
            try {
                const formData = new FormData();
                formData.append("image", this.selectedFile);
                // ✅ Muy importante: establecer correctamente el encabezado
                const res = await api.post('/Actualizar/UserImagen', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.selectedFile = null;
                this.preview = null;
                // 🔄 Refresca los datos del usuario
                await this.$store.dispatch('auth/fetchUser');
                this.$swal.fire('Éxito', '✅ Se actualizó correctamente la imagen de Usuario', 'success');
            } catch (error) {
                console.error("Error al subir la imagen:", error);
                this.$swal.fire('Error', '❌ Error al subir la imagen.', 'error');
            }
        },
        async guardar() {
            try {
                const filteredUser = _.pick(this.user, ['name', 'email', 'telefono', 'domicilio', 'localidadColonia', 'password_hold', 'password', 'password_confirmation']);
                await api.post('/Actualizar/User', filteredUser);
                this.errores = {}; // 🔄 Limpia los errores
                await this.$store.dispatch('auth/fetchUser');
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

    }
};
</script>