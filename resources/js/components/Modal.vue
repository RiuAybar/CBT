<template>
    <div class="modal fade" tabindex="-1" :id="id" ref="modalElement">
        <div class="modal-dialog" :class="modalSize">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> {{ title }}</h5>
                    <button type="button" class="btn-close" @click="cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- contenido dinámico -->
                    <slot />
                </div>
                <div class="modal-footer">
                    <slot name="footer" />
                    <button class="btn btn-secondary" @click="cerrar">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from 'bootstrap';

export default {
    name: 'Modal',
    props: {
        id: { type: String, required: true },
        title: { type: String, default: '' },
        size: { type: String, default: 'xl' } // sm, lg, xl
    },
    data() {
        return {
            modal: null
        };
    },
    computed: {
        modalSize() {
            return this.size ? `modal-${this.size}` : '';
        }
    },
    mounted() {
        this.modal = new Modal(this.$refs.modalElement);
    },
    methods: {
        abrir() {
            this.modal.show();
        },
        cerrar() {
            this.modal.hide();
            // 🧠 Remueve foco para evitar el warning de accesibilidad
            document.activeElement?.blur();
        },
    }
};
</script>