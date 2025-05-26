<template>
    <div class="content">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Profile</h1>
            <a class="badge bg-dark text-white ms-2" href="upgrade-to-pro.html">
                Get more page examples
            </a>
        </div>
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detalles del perfil</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="" :alt="getUser?.name" class="img-fluid rounded-circle mb-2" width="128"
                            height="128" />
                        <h5 class="card-title mb-0">{{ getUser?.name }}</h5>
                        <div class="text-muted mb-2">Lead Developer</div>

                        <div>
                            <a class="btn btn-primary btn-sm me-2" href="#">Follow</a>
                            <a class="btn btn-primary btn-sm" href="#"><span data-feather="message-square"></span>
                                Message</a>
                        </div>
                    </div>


                </div>
            </div>

            <div class="col-md-8 col-xl-9">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Disabled</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" form="name">Nombre Completo</label>
                                <input v-model="user.name" name="name" type="text" class="form-control" placeholder="Nombre Completo" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" form="email">Email</label>
                                <input name="name" type="email" class="form-control" placeholder="Correo electronico" />
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label" form="password_hold">Contraseña anterior</label>
                                <input name="password_hold" type="password" class="form-control"
                                    placeholder="Contraseña anterior" />
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label" form="password">Contraseña nueba</label>
                                <input name="password" type="password" class="form-control" placeholder="Contraseña" />
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label" form="password_confirmation">confirmacion de
                                    contraseña</label>
                                <input name="password_confirmation" type="password" class="form-control"
                                    placeholder="confirmacion de contraseña" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex';
export default {
    name: 'ConfiguracionUsuarios',
    data() {
        return {
            user: {
                id: null,
                name: null,
                email: null,
            },
        }
    },
    methods: {
        ...mapActions(['toggleCollapsed']),
        collapseMenu() {
            this.toggleCollapsed();
        }
    },
    computed: {
        ...mapGetters('auth', ['isAuthenticated', 'getUser']),
    },
    mounted() {
        this.user = this.getUser;
    }
}
</script>