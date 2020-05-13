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
            <tbody v-for="(data, index) in employes.data" :key="index.id">
              <td>
                <a href="" @click.prevent="openModal('show', data)">{{data.id | addCero}}</a>
              </td>
              <td v-text="data.documento"></td>
              <td>{{data.nombres}} {{data.apellidos}} </td>
              <td>{{data.position.nombre}}</td>
              <td v-if="data.estado_contrato == '1'">
                Activo
              </td>
              <td v-else-if="data.estado_contrato == '0'">
                Inactivo
              </td>
              <td v-if="data.immediate_boss">
                <strong>{{data.immediate_boss.nombres}} {{data.immediate_boss.apellidos}}</strong> <br>
                <i>{{data.immediate_boss.position.nombre}}</i>
              </td>
              <td v-else></td>
              <td>
                <button class="btn btn-warning" @click.prevent="openModal('update', data)">Editar</button>
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
            <h5 class="modal-title" id="modalEmployeLabel" v-if="typeModal === 'new'">
              Crear Empleado
            </h5>
            <h5 class="modal-title" id="modalEmployeLabel" v-else-if="typeModal === 'update'">
              Editar Empleado
            </h5>
            <h5 class="modal-title" id="modalEmployeLabel" v-else>
              Información del Empleado
            </h5>
            <button type="button" class="close" @click.prevent="closeModal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form enctype="multipart/form-data">
              <div class="form-group row">
                <label for="documento" class="col-md-4 col-form-label font-weight-bold">Documento: </label>
                <div class="col-md-8">
                  <input v-model="formData.documento" type="text" class="form-control" id="documento" v-if="typeModal != 'show'" :class="{'is-invalid' : $v.formData.documento.$invalid, 'is-valid' : !$v.formData.documento.$invalid}">
                  <span class="form-control" v-else>{{formData.documento}}</span>
                  <div class="invalid-feedback" v-if="!$v.formData.documento.required">
                    Campo documento es obligatorio.
                  </div>
                  <div class="invalid-feedback" v-if="!$v.formData.documento.maxLength">
                    Campo documento solo puede tener {{$v.formData.documento.$params.maxLength.max}} caracteres.
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="nombres" class="col-md-4 col-form-label font-weight-bold">Nombres: </label>
                <div class="col-md-8">
                  <input v-model="formData.nombres" type="text" class="form-control" id="nombres" v-if="typeModal != 'show'" :class="{'is-invalid' : $v.formData.nombres.$invalid, 'is-valid' : !$v.formData.nombres.$invalid}">
                  <span class="form-control" v-else>{{formData.nombres}}</span>
                  <div class="invalid-feedback" v-if="!$v.formData.nombres.required">
                    Campo nombres es obligatorio.
                  </div>
                  <div class="invalid-feedback" v-if="!$v.formData.nombres.maxLength">
                    Campo nombres solo puede tener {{$v.formData.nombres.$params.maxLength.max}} caracteres.
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="apellidos" class="col-md-4 col-form-label font-weight-bold">Apellidos: </label>
                <div class="col-md-8">
                  <input v-model="formData.apellidos" type="text" class="form-control" id="apellidos" v-if="typeModal != 'show'" :class="{'is-invalid' : $v.formData.apellidos.$invalid, 'is-valid' : !$v.formData.apellidos.$invalid}">
                  <span class="form-control" v-else>{{formData.apellidos}}</span>
                  <div class="invalid-feedback" v-if="!$v.formData.apellidos.required">
                    Campo apellidos es obligatorio.
                  </div>
                  <div class="invalid-feedback" v-if="!$v.formData.apellidos.maxLength">
                    Campo apellidos solo puede tener {{$v.formData.apellidos.$params.maxLength.max}} caracteres.
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="fechaNacimiento" class="col-md-4 col-form-label font-weight-bold">Fecha Nacimiento: </label>
                <div class="col-md-8">
                  <input v-model="formData.fecha_nacimiento" type="date" class="form-control" id="fechaNacimiento" v-if="typeModal != 'show'" :class="{'is-invalid' : $v.formData.fecha_nacimiento.$invalid, 'is-valid' : !$v.formData.fecha_nacimiento.$invalid}">
                  <span class="form-control" v-else>{{formData.fecha_nacimiento}}</span>
                  <div class="invalid-feedback" v-if="!$v.formData.nombres.required">
                    Campo fecha de nacimiento es obligatorio.
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="sexo" class="col-md-4 col-form-label font-weight-bold">Sexo: </label>
                <div class="col-md-8 my-auto" v-if="typeModal != 'show'">
                  <div class="form-check form-check-inline check_pointer">
                    <input v-model="formData.sexo" class="form-check-input" type="radio" name="radioButtonSexo" id="checkH" value="H" :class="{'is-invalid' : $v.formData.sexo.$invalid, 'is-valid' : !$v.formData.sexo.$invalid}">
                    <label class="form-check-label" for="checkH">Hombre</label>
                  </div>
                  <div class="form-check form-check-inline check_pointer">
                    <input v-model="formData.sexo" class="form-check-input" type="radio" name="radioButtonSexo" id="checkM" value="M" :class="{'is-invalid' : $v.formData.sexo.$invalid, 'is-valid' : !$v.formData.sexo.$invalid}">
                    <label class="form-check-label" for="checkM">Mujer</label>
                  </div>
                  <div class="invalid-feedback" style="display: block;" v-if="!$v.formData.sexo.required">
                    Campo sexo es obligatorio.
                  </div>
                </div>
                <div class="col-md-8 my-auto" v-else>
                  <span class="form-control">{{formData.sexo =='H' ? 'Hombre' : 'Mujer'}}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-4 col-form-label font-weight-bold">Foto: </label>
                <div class="col-md-8 upload_file mb-3" v-if="typeModal != 'show'">
                  <input
                    type="file"
                    class="custom-file-input"
                    name="imagen"
                    id="imagen"
                    lang="es"
                    @change="obtenerImagen"
                    :class="{'is-invalid' : $v.foto.$invalid && typeModal != 'update', 'is-valid' : !$v.foto.$invalid}"
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
                  <div class="invalid-feedback" v-if="!$v.foto.required && typeModal != 'update'">
                    Campo foto es obligatorio.
                  </div>
                </div>
                <img
                  class="rounded mx-auto d-block img-fluid"
                  width="100"
                  :src="fotoMiniatura"
                />
              </div>
              <div class="form-group row">
                <label for="estado" class="col-md-4 col-form-label font-weight-bold">Estado Contrato: </label>
                <div class="col-md-8 my-auto" v-if="typeModal != 'show'">
                  <div class="form-check form-check-inline check_pointer">
                    <input v-model="formData.estado_contrato" class="form-check-input" type="radio" name="radioButtonEstado" id="checkA" value="1" :class="{'is-invalid' : $v.formData.estado_contrato.$invalid, 'is-valid' : !$v.formData.estado_contrato.$invalid}">
                    <label class="form-check-label" for="checkA">Activo</label>
                  </div>
                  <div class="form-check form-check-inline check_pointer">
                    <input v-model="formData.estado_contrato" class="form-check-input" type="radio" name="radioButtonEstado" id="checkI" value="0" :class="{'is-invalid' : $v.formData.estado_contrato.$invalid, 'is-valid' : !$v.formData.estado_contrato.$invalid}">
                    <label class="form-check-label" for="checkI">Inactivo</label>
                  </div>
                  <div class="invalid-feedback" style="display: block;" v-if="!$v.formData.estado_contrato.required">
                    Campo estado contrato es obligatorio.
                  </div>
                </div>
                <div class="col-md-8 my-auto" v-else>
                  <span class="form-control">{{formData.estado_contrato === 1 ? 'Activo' : 'Inactivo'}}</span>
                </div>
              </div>
              <div class="form-group row">
                <label for="estadocivil" class="col-md-4 col-form-label font-weight-bold">Estado Civil: </label>
                <div class="col-md-8" v-if="typeModal != 'show'">
                  <select
                    class="custom-select"
                    v-model="formData.civilstate_id"
                    :class="{'is-invalid' : $v.formData.civilstate_id.$invalid, 'is-valid' : !$v.formData.civilstate_id.$invalid}"
                    >
                    <option value='' selected disabled>Seleccionar...</option>
                    <option
                      :value="civilstate.id"
                      v-for="(civilstate, index) in civilStates" :key="index">
                      {{civilstate.nombre}}
                    </option>
                  </select>
                  <div class="invalid-feedback" v-if="!$v.formData.civilstate_id.required">
                    Campo estado civil es obligatorio.
                  </div>
                </div>
                <div class="col-md-8" v-else>
                  <span class="form-control">{{formData.civilstate_id}}</span>
                </div>
              </div>
              <div class="form-group row">
                <label for="cargo" class="col-md-4 col-form-label font-weight-bold">Cargo: </label>
                <div class="col-md-8" v-if="typeModal != 'show'">
                  <select
                    class="custom-select"
                    v-model="formData.position_id"
                    :class="{'is-invalid' : $v.formData.position_id.$invalid, 'is-valid' : !$v.formData.position_id.$invalid}"
                    @change="getImmediateBosses">
                    <option value='' selected disabled>Seleccionar...</option>
                    <option
                      :value="position.id"
                      v-for="(position, index) in positions" :key="index"
                      >
                      {{position.nombre}}
                    </option>
                  </select>
                  <div class="invalid-feedback" v-if="!$v.formData.civilstate_id.required">
                    Campo cargo es obligatorio.
                  </div>
                </div>
                <div class="col-md-8" v-else>
                  <span class="form-control">{{formData.position_id}}</span>
                </div>
              </div>
              <div class="form-group row">
                <label for="jefeacargo" class="col-md-4 col-form-label font-weight-bold">Jefe a Cargo: </label>
                <div class="col-md-8" v-if="typeModal != 'show'">
                  <select
                    class="custom-select"
                    v-model="formData.immediateboss_id"
                    >
                    <option value='' selected disabled>Seleccionar...</option>
                    <!-- <option
                      :value="immediateBoss.id"
                      v-for="(immediateBoss, index) in immediateBosses" :key="index"
                      >
                      {{immediateBoss.nombres}} {{immediateBoss.apellidos}} - {{immediateBoss.position.nombre}}
                    </option> -->
                  </select>
                </div>
                <div class="col-md-8" v-else>
                  <span class="form-control">{{formData.immediateboss_id}}</span>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" @click.prevent="closeModal" v-if="typeModal != 'show'">
              Cancelar
            </button>
            <button type="button" class="btn btn-danger" @click.prevent="closeModal" v-else>
              Cerrar
            </button>
            <button v-if="typeModal === 'new'" type="button" class="btn btn-success" @click.prevent="saveEmploye">
              Guardar
            </button>
            <button v-if="typeModal === 'update'" type="button" class="btn btn-success" @click.prevent="updateEmploye">
              Actualizar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
//importamos las reglas a validar de la API vuelidate
import { required, maxLength } from 'vuelidate/lib/validators'
  export default {
    data() {
      return {
        id: '',
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
        typeModal: '',
        allEmployes: [],
        employes: {},
        civilStates: [],
        positions: [],
        immediateBosses: []
      }
    },
    validations: {
      formData:{
        documento: {required, maxLength: maxLength(15)},
        nombres: {required, maxLength: maxLength(255)},
        apellidos: {required, maxLength: maxLength(255)},
        fecha_nacimiento: {required},
        sexo: {required},
        estado_contrato: {required},
        civilstate_id: {required},
        position_id: {required},
        immediateboss_id: ''
      },
      foto: {required}
    },
    filters:{
      /* Funcion para agregar los 0 al inicio del num */
      addCero(value){
        let n = Math.abs(value);
        let zeros = Math.max(0, 4 - Math.floor(n).toString().length );
        let zeroString = Math.pow(10,zeros).toString().substr(1);

        return zeroString+n;
      }
    },
    computed: {
      changePosition(){
        return this.formData.position_id
      }
    },
    watch: {
      //cuando se cambia el cargo
      changePosition: function (val) {
        let positionAfter = val
        let value = positionAfter != '' ? --positionAfter : ''

        //filtramos los empleados que cumplan la condición
        this.immediateBosses.push(val) /* = this.allEmployes.filter(em => (em.position_id == value && em.id != this.id && em.estado_contrato == 1)) */

        console.log(this.immediateBosses)
      }
    },
    methods: {
      /* al abrir la modal recibimos 2 parametros */
      openModal(type, data){
        $('#modalEmploye').modal('show')
        switch (type) {
          case 'new':
            this.typeModal = 'new'
            break;

          case 'update':
            this.typeModal = 'update'
            this.id = data['id']
            this.formData.documento = data['documento']
            this.formData.nombres = data['nombres']
            this.formData.apellidos = data['apellidos']
            this.formData.fecha_nacimiento = data['fecha_nacimiento']
            this.formData.sexo = data['sexo']
            this.formData.estado_contrato = data['estado_contrato']
            this.formData.civilstate_id = data['civilstate_id']
            this.formData.position_id = data['position_id']
            this.fotoMiniatura = data['foto']
            data['immediateboss_id'] ? this.formData.immediateboss_id = data['immediateboss_id'] : this.formData.immediateboss_id = null

            break;

          case 'show':
            this.typeModal = 'show'
            this.formData.documento = data['documento']
            this.formData.nombres = data['nombres']
            this.formData.apellidos = data['apellidos']
            this.formData.fecha_nacimiento = data['fecha_nacimiento']
            this.formData.sexo = data['sexo']
            this.formData.estado_contrato = data['estado_contrato']
            this.formData.civilstate_id = data['civilstate']['nombre']
            this.formData.position_id = data['position']['nombre']
            //obtenemos el Nombre del Jefe
            data['immediateboss_id'] ? this.formData.immediateboss_id = data['immediate_boss']['nombres'] + ' ' + data['immediate_boss']['apellidos'] : this.formData.immediateboss_id = ''

            this.fotoMiniatura = data['foto']
            break;

          default:
            break;
        }
      },
      closeModal(){
        $('#modalEmploye').modal('hide')
        this.id = ''
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
      //se resetea el valor de jefe inmediato
      getImmediateBosses(){
        this.formData.immediateboss_id = ''
      },
      saveEmploye(){
        if (this.$v.$invalid) {
          this.$swal({
            position: 'top',
            icon: 'error',
            title: 'Errores en el formulario, verificar!',
            showConfirmButton: true,
            confirmButtonText: 'Aceptar',
            //timer: 1800
          });
        }else{
          /* enviamos la informacion del formulario */
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
            this.getAllEmployes()
            this.getEmployes(this.employes.current_page)
            this.closeModal()
          })
          .catch(err => {
            console.error(err);
          })
        }
      },
      updateEmploye(id){
        let data = new FormData();
        data.append("id", this.id);
        data.append("data", JSON.stringify(this.formData));
        data.append("foto", this.foto);

        axios.post('employe/update', data, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
          })
        .then(res => {
          this.$swal({
            position: 'top',
            icon: 'success',
            title: 'Empleado editado exitosamente!',
            //showConfirmButton: true,
            //confirmButtonText: 'Aceptar',
            timer: 1800
          });
          this.getAllEmployes()
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
            paginate: true,
            page: page
          }
        }).then(res => {
            this.employes = res.data
          })
          .catch(err => {
            console.error(err);
          })
      },
      getAllEmployes(){
        axios.get('employe/get')
          .then(res => {
            this.allEmployes = res.data
          })
          .catch(err => {
            console.error(err);
          })
      }
    },
    async created() {
      this.getCivilStates()
      this.getPositions()
      this.getEmployes()
      this.getAllEmployes()
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
      .form-check-input.is-valid ~ .form-check-label{
        color: black !important;
      }
    }
</style>
