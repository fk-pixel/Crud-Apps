
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP-Vue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
<div class="container" id="app">
   <br />
   <h3 align="center">Vue.js with PHP</h3>
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
     <div class="row">
      <div class="col-md-6">
       <h3 class="panel-title">Sample Data</h3>
      </div>
      <div class="col-md-6" align="right">
       <input type="button" class="btn btn-success btn-xs" placeholder="Send your data" @click="openModel" value="Add" />
      </div>
     </div>
    </div>
    <div class="panel-body">
     <div class="table-responsive">
      <table class="table table-bordered table-striped">
       <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Edit</th>
        <th>Delete</th>
       </tr>
       <tr v-for="row in allData">
        <td>{{ row.first_name }}</td>
        <td>{{ row.last_name }}</td>
        <td><button type="button" name="edit" class="btn btn-primary btn-xs edit" @click="getItem(row.id)">Edit</button></td>
        <td><button type="button" name="delete" class="btn btn-danger btn-xs delete" @click="deleteItem(row.id)">Delete</button></td>
       </tr>
      </table>
     </div>
    </div>
   </div>
   <div v-if="myModel">
    <transition name="model">
     <div class="modal-mask">
      <div class="modal-wrapper">
       <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" @click="myModel=false"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">{{ dynamicTitle }}</h4>
         </div>
         <div class="modal-body">
          <div class="form-group">
           <label>First Name</label>
           <input type="text" class="form-control" v-model="first_name" />
          </div>
          <div class="form-group">
           <label>Last Name</label>
           <input type="text" class="form-control" v-model="last_name" />
          </div>
          <br />
          <div align="center">
           <input type="hidden" v-model="hiddenId" />
           <input type="button" class="btn btn-success btn-xs" v-model="actionButton" @click="submitData" />
          </div>
         </div>
        </div>
       </div>
      </div>
     </div>
    </transition>
   </div>
  </div>
</body>
</html>

<script>
    var app = new Vue({
        el: '#app',
        data() {
            return {
            items: [],
            myModel: false,
            actionButton: 'Insert',
            dynamicTitle: 'Add Data'
            },
        },
        methods: {
            getItems() {
                axios.post('action.php', {
                    action:'getItems'
                }).then((res) => {
                    res.items = res.data
                });
            },
            openModel(){
                app.first_name = '',
                app.last_name = '';
                app.actionButton= 'Insert';
                app.dynamicTitle = 'Add Data';
                app.myModel = true;
            },
            submitData() {
                if(app.first_name != '' && app.last_name != '') {
                    if(app.actionButton == 'Insert') {
                        axios.post('action.php', {
                            action: 'insert',
                            firstName = app.first_name,
                            lastName = app.last_name,
                        }).then((res) => {
                            app.myModel = false;
                            app.getItems();
                            app.first_name = '';
                            app.last_name = '',
                            alert(res.data.message);
                        });
                    }
                    if(app.actionButton == 'Update') {
                        axios.post('action.php', {
                            action: 'update',
                            firstName = app.first_name,
                            lastName = app.last_name,
                            hiddenId = app.hiddenId
                        }).then((res) => {
                            app.Mymodel = false;
                            app.getItems();
                            app.first_name = '';
                            app.last_name = '';
                            app.hiddenId = '';
                            alert(res.data.message);
                        });
                    }
                } else {
                    alert('Fill All Fields');
                }
            },
            getItem(id) {
                axios.post('action.php', {
                    action: 'getItem',
                    id: id
                }).then((res) => {
                    app.first_name = res.data.first_name;
                    app.last_name = res.data.last_name;
                    app.hiddenId = res.data.id;
                    app.myModel = true;
                    app.actionButton = 'Update';
                    app.dynamicTitle = 'Edit Data';
                });
            },
            deleteItem() {
                if(confirm("Are you sure want to remove this data?")){
                    axios.post('action.php', {
                    action: 'delete',
                    id: id 
                }).then((res) => {
                    app.getItems();
                    alert(res.data.message);
                });
                }
            }
        },
        created() {
            this.getItems();
        }
    });

</script>