<template>
    <div class="index_css">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        <button class="btn btn-danger" @click="showAlert">Hello world</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        methods: {
            showAlert(){
                this.$swal('Hello Vue world!!!');
            }
        },
    }
</script>
<style lang="scss">
    .index_css{
        margin-top: 5rem;
    }
</style>
