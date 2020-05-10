<template>
  <div class="index_css">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <button class="btn btn-primary" data-toggle="modal" @click="openModal('new')">
            Nuevo Empleado
          </button>
        </div>

        <div class="card-body table-responsive">
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Documento</th>
                <th scope="col">Empleado</th>
                <th scope="col">Cargo</th>
                <th scope="col">Estado</th>
                <th scope="col">Jefe</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <!-- <tbody v-if="!employes.data">
              <td colspan="7">
                <div role="alert" class="alert alert-danger text-center">
                    <div class="form-group">
                      <strong>
                          <h5>¡Sin información!</h5>
                      </strong>
                    </div>
                </div>
                </td>
            </tbody> -->
            <tbody v-for="(data, index) in employes.data" :key="index.id">
              <td>
                <a href="">{{data.id | addCero}}</a>
              </td>
              <td v-text="data.documento"></td>
              <td>{{data.nombres}} {{data.apellidos}} </td>
              <td>{{data.position.nombre}}</td>
              <td v-if="data.estado_contrato">Activo</td>
              <td v-else>Inactivo</td>
              <td v-if="data.immediate_boss">
                <strong>{{data.immediate_boss.nombres}} {{data.immediate_boss.apellidos}}</strong> <br>
                <i>{{data.immediate_boss.position.nombre}}</i>
              </td>
              <td v-else></td>
              <td>
                <button class="btn btn-warning">Editar</button>
                <button class="btn btn-danger" @click.prevent="deleteData(data.id)" >Eliminar</button>
              </td>
            </tbody>
          </table>
          <div class="text-left">
            <h5>Total registros: <strong>{{employes.total}}</strong></h5>
          </div>
          <!-- Implementa el vue pagination para poder cambiar pagina -->
          <pagination
          :data="employes"
          @pagination-change-page="getEmployes"
          align="center"
          :limit="4"
          ></pagination>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalEmploye" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalEmployeLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEmployeLabel">
              Crear Empleado
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form enctype="multipart/form-data">
              <div class="form-group row">
                <label for="documento" class="col-md-4 col-form-label font-weight-bold">Documento</label>
                <div class="col-md-8">
                  <input v-model="formData.documento" type="text" class="form-control" id="documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="nombres" class="col-md-4 col-form-label font-weight-bold">Nombres</label>
                <div class="col-md-8">
                  <input v-model="formData.nombres" type="text" class="form-control" id="nombres">
                </div>
              </div>
              <div class="form-group row">
                <label for="apellidos" class="col-md-4 col-form-label font-weight-bold">Apellidos</label>
                <div class="col-md-8">
                  <input v-model="formData.apellidos" type="text" class="form-control" id="apellidos">
                </div>
              </div>
              <div class="form-group row">
                <label for="fechaNacimiento" class="col-md-4 col-form-label font-weight-bold">Fecha Nacimiento</label>
                <div class="col-md-8">
                  <input v-model="formData.fecha_nacimiento" type="date" class="form-control" id="fechaNacimiento">
                </div>
              </div>
              <div class="form-group row">
                <label for="sexo" class="col-md-4 col-form-label font-weight-bold">Sexo</label>
                <div class="col-md-8 my-auto">
                  <div class="form-check form-check-inline check_pointer">
                    <input v-model="formData.sexo" class="form-check-input" type="radio" name="radioButtonSexo" id="checkH" value="H">
                    <label class="form-check-label" for="checkH">Hombre</label>
                  </div>
                  <div class="form-check form-check-inline check_pointer">
                    <input v-model="formData.sexo" class="form-check-input" type="radio" name="radioButtonSexo" id="checkM" value="M">
                    <label class="form-check-label" for="checkM">Mujer</label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-4 col-form-label font-weight-bold">Foto</label>
                <div class="col-md-8 upload_file mb-3">
                  <input
                    type="file"
                    class="custom-file-input"
                    name="imagen"
                    id="imagen"
                    lang="es"
                    @change="obtenerImagen"
                    />
                  <label
                    class="custom-file-label"
                    for="file"
                    v-if="!foto"
                    >Elegir Foto
                  </label>
                  <label
                    class="custom-file-label"
                    for="file"
                    v-else>
                    {{foto.name}}
                  </label>
                </div>
                <img
                  class="rounded mx-auto d-block img-fluid"
                  width="100"
                  :src="fotoMiniatura"
                />
              </div>
              <div class="form-group row">
                <label for="estado" class="col-md-4 col-form-label font-weight-bold">Estado Contrato</label>
                <div class="col-md-8 my-auto">
                  <div class="form-check form-check-inline check_pointer">
                    <input v-model="formData.estado_contrato" class="form-check-input" type="radio" name="radioButtonEstado" id="checkA" value="1">
                    <label class="form-check-label" for="checkA">Activo</label>
                  </div>
                  <div class="form-check form-check-inline check_pointer">
                    <input v-model="formData.estado_contrato" class="form-check-input" type="radio" name="radioButtonEstado" id="checkI" value="0">
                    <label class="form-check-label" for="checkI">Inactivo</label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="estadocivil" class="col-md-4 col-form-label font-weight-bold">Estado Civil</label>
                <div class="col-md-8">
                  <select class="custom-select" v-model="formData.civilstate_id">
                    <option value='' selected disabled>Seleccionar...</option>
                    <option
                      :value="civilstate.id"
                      v-for="(civilstate, index) in civilStates" :key="index">
                      {{civilstate.nombre}}
                    </option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="cargo" class="col-md-4 col-form-label font-weight-bold">Cargo</label>
                <div class="col-md-8">
                  <select
                    class="custom-select"
                    v-model="formData.position_id"
                    @change="getImmediateBosses(formData.position_id)">
                    <option value='' selected disabled>Seleccionar...</option>
                    <option
                      :value="position.id"
                      v-for="(position, index) in positions" :key="index"
                      >
                      {{position.nombre}}
                    </option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="jefeacargo" class="col-md-4 col-form-label font-weight-bold">Jefe a Cargo</label>
                <div class="col-md-8">
                  <select class="custom-select" v-model="formData.immediateboss_id">
                    <option value='' selected disabled>Seleccionar...</option>
                    <option
                      :value="immediateBoss.id"
                      v-for="(immediateBoss, index) in immediateBosses" :key="index"
                      >
                      {{immediateBoss.nombres}} {{immediateBoss.apellidos}} - {{immediateBoss.position.nombre}}
                    </option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">
              Cancelar
            </button>
            <button type="button" class="btn btn-success" @click.prevent="saveEmploye">
              Guardar
            </button>
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
        formData:{
          documento: '',
          nombres: '',
          apellidos: '',
          fecha_nacimiento: '',
          sexo: '',
          estado_contrato: '',
          civilstate_id: '',
          position_id: '',
          immediateboss_id: ''
        },
        foto: '',
        fotoMiniatura: '',
        employes: {},
        civilStates: [],
        positions: [],
        immediateBosses: []
      }
    },
    filters:{
      /* Funcion para agregar los 0 al inicio del num */
      addCero(value){
        let n = Math.abs(value);
        let zeros = Math.max(0, 4 - Math.floor(n).toString().length );
        let zeroString = Math.pow(10,zeros).toString().substr(1);
        if( 4 < 0 ) {
            zeroString = '-' + zeroString;
        }

        return zeroString+n;
      }
    },
    methods: {
      openModal(type){
        $('#modalEmploye').modal('show')
      },
      closeModal(){
        $('#modalEmploye').modal('hide')
        this.foto = ''
        this.fotoMiniatura = ''
        this.formData.documento = ''
        this.formData.nombres = ''
        this.formData.apellidos = ''
        this.formData.fecha_nacimiento = ''
        this.formData.sexo = ''
        this.formData.estado_contrato = ''
        this.formData.civilstate_id = ''
        this.formData.position_id = ''
        this.formData.immediateboss_id = ''
      },
      obtenerImagen(e) {
        this.foto = event.target.files[0]
        /* if (this.formData.foto.type === 'image/png') {
          this.$swal('La imagen es PNG');

        } */
        //console.log(this.foto);
        this.showImage(this.foto)
      },
      showImage(file) {
        let me = this
        let reader = new FileReader() //manejar archivos
        //la e hace referencia a file
        reader.onload = e => {
          me.fotoMiniatura = e.target.result
        }
        reader.readAsDataURL(file) //url de imagen cargada
      },
      getCivilStates(){
        axios.get('civilstate/get')
        .then(res => {
          this.civilStates = res.data
        })
        .catch(err => {
          console.error(err);
        })
      },
      getPositions(){
        axios.get('position/get')
        .then(res => {
          this.positions = res.data
        })
        .catch(err => {
          console.error(err);
        })
      },
      getImmediateBosses(id){
        this.formData.immediateboss_id = ''
        axios.get('employe/getImmediateBoss', {
              params: {
                position_id: id
              }
            })
          .then(res => {
            this.immediateBosses = res.data
          })
          .catch(err => {
            console.error(err);
          })
      },
      saveEmploye(){ /* enviamos la informacion del formulario */
        let data = new FormData();
        data.append("data", JSON.stringify(this.formData));
        data.append("foto", this.foto);

        axios.post('employe/create', data, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
          })
        .then(res => {
          this.$swal({
            position: 'top',
            icon: 'success',
            title: 'Empleado creado exitosamente!',
            //showConfirmButton: true,
            //confirmButtonText: 'Aceptar',
            timer: 1800
          });
          this.getEmployes(this.employes.current_page)
          this.closeModal()
        })
        .catch(err => {
          console.error(err);
        })
      },
      deleteData(id){
        let me = this
        me.$swal({
          title: '¿Está seguro de eliminar el Empleado?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar!',
          cancelButtonText: 'No, cancelar'
        }).then((result) => {
          if (result.value) {
            axios.put('employe/delete',{id: id})
            .then(res => {
              me.getEmployes(me.employes.current_page)
              if (res.type === 'success') {
                let message = res.data.message
                me.$swal({
                  position: 'top',
                  icon: 'success',
                  title: `${message}`,
                  //showConfirmButton: true,
                  //confirmButtonText: 'Aceptar',
                  timer: 1800
                });
              }else{
                let message = res.data.message
                me.$swal({
                  position: 'top',
                  icon: 'warning',
                  title: `${message}`,
                  showConfirmButton: true,
                  confirmButtonText: 'Aceptar',
                  //timer: 1800
                });
              }
            })
            .catch(err => {
              console.error(err);
            })
          }
        })
      },
      getEmployes(page){
        axios.get('employe/get', {
          params:{
            page: page
          }
        }).then(res => {
            this.employes = res.data
          })
          .catch(err => {
            console.error(err);
          })
      },
    },
    async created() {
      this.getCivilStates()
      this.getPositions()
      this.getEmployes()
    },
  }
</script>
<style lang="scss">
    .index_css{
      margin-top: 5rem;
      .upload_file{
        max-width: 61%;
        margin-left: 15px;
      }
      .modal-content{
        z-index: 999;
      }
      .card-body {
      z-index: 5;
      }
      .check_pointer{
        .form-check-input:hover{
          cursor: pointer;
        }
        .form-check-label:hover{
          cursor: pointer;
        }
        cursor: pointer;
      }
    }
</style>
