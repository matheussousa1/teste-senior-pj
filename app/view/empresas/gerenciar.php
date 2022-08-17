<style type="text/css">
  #new-search-area {
    width: 100%;
    clear: both;
    padding-top: 20px;
    padding-bottom: 20px;
  }
  #new-search-area input {
      width: 600px;
      font-size: 20px;
      padding: 5px;
  }
</style>
<script type="text/javascript">
$(document).ready( function () {

  $(".cnpj").mask("99.999.999/9999-99");

  // ativar o tooltip
  $('body').tooltip({selector: '[data-toggle="tooltip"]'});

  $('#tabela').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "responsive": true,
    "autoWidth": false,
    "pageLength": 10,
    dom: 'Bfrtip',
    buttons: [
      'excel', 'print'
    ],
    "ajax": {
      "url": "<?php echo AJAX; ?>empresas.php?acao=buscar",
      "type": "GET"
    },
    "language": {
      "url": "https://cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json",
      buttons: {
        print: 'Imprimir'
      }
    },
    initComplete : function() {
      $("#tabela_filter").detach().appendTo('#new-search-area');
    },    
    "createdRow": function ( row, data, index ) {
      if(data['ativo'] == 0){
        $(row).addClass('table-danger');
      }
    },
    "columns": [
      { "data": "nome" },
      { "data": "cnpj" },
      { "data": "endereco" },
      { "data": "dataCadastro" },
      { "data": "button" }
    ]
  });

  // adicionar unser
  $(document).on("click","#btnadd",function(){
    $("#modal_add").modal("show");
    $("#nome").focus();
  });


  $('#formCadastrar').validate({
    rules: {
      nome : { required: true},
      cnpj : { required: true},
      endereco : { required: true},
    },
    messages: {
      nome : { required: 'Preencha este campo' },
      cnpj : { required: 'Preencha este campo'},
      endereco : { required: 'Preencha este campo'},
    },
    submitHandler: function( form ){
      var dados = $('#formCadastrar').serialize();
      $.ajax({
        type: "GET",
        url: "<?php echo AJAX; ?>empresas.php?acao=cadastrar",
        data: dados,
        dataType: 'json',
        success: function(res) {
          if(res.status == 200){
            swal({   
              title: "Cadastrado com Sucesso",  
              type: "success",   
              showConfirmButton: false,
            });
            window.setTimeout(function(){
              $('#formCadastrar input').val(""); 
              swal.close();
              var table = $('#tabela').DataTable(); 
              table.ajax.reload( null, false );
              $("#modal_add").modal("hide");
            } ,2500);
          }else{
            swal({   
              title: "Error",  
              type: "error",   
              showConfirmButton: false,
            });
            window.setTimeout(function(){
              swal.close();
            } ,2500);
          }
        }
      });
      return false;
    }
  });
     //abrir modal pra edição
    $(document).on("click",".btnedit",function(){
      var id_user = $(this).attr("id_user");
      var value = {
        id: id_user
      };
      $.ajax({
        url : "<?php echo AJAX; ?>empresas.php?acao=buscarDados",
        type: "GET",
        data : value,
        success: function(data, textStatus, jqXHR){
          var data = jQuery.parseJSON(data);
          $("#nomeEdit").val(data.nome);
          $("#cnpjEdit").val(data.cnpj);
          $("#enderecoEdit").val(data.endereco);
          $("#idObj").val(data.id);
          $("#moda_edit").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown){
          swal("Error!", textStatus, "error");
        }
      });
    });

    $('#formCadastrarEdit').validate({
      rules: {
        nome : { required: true},
        cnpj : { required: true},
        endereco : { required: true},
      },
      messages: {
        nome : { required: 'Preencha este campo' },
        cnpj : { required: 'Preencha este campo'},
        endereco : { required: 'Preencha este campo'},
      },
      submitHandler: function( form ){
        var dados = $('#formCadastrarEdit').serialize();
        $.ajax({
          type: "GET",
          url: "<?php echo AJAX; ?>empresas.php?acao=editar",
          data: dados,
          dataType: 'json',
          crossDomain: false,
          success: function(res) {
            if(res.status == 200){
              swal({   
                title: "Alterado com Sucesso",  
                type: "success",   
                showConfirmButton: false,
              });
              window.setTimeout(function(){
                $('#formCadastrarEdit input').val(""); 
                swal.close();
                  var table = $('#tabela').DataTable(); 
                  table.ajax.reload( null, false );
                  $("#moda_edit").modal("hide");
              } ,2500);
            }else{
              swal({   
                title: "Error",  
                type: "error",   
                showConfirmButton: false,
              });
              window.setTimeout(function(){
                swal.close();
              } ,2500);
            }
          }
        });
        return false;
      }
    });

    // inativar usuarios
     $(document).on( "click",".btndel", function() {
      var id_user = $(this).attr("id_user");
      var name = $(this).attr("nome_user");
      swal({   
        title: "Inativar",   
        text: "Inativar: "+name+" ?",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Inativar",   
        closeOnConfirm: true}).then(function(){   
          $.ajax({
          type: "GET",
          url: "<?php echo AJAX; ?>empresas.php",
          data: {'acao':'deletar', 'id': id_user},
          dataType: 'json',
          success: function(res) {
            if(res.status == 200){
              swal({   
                title: "Alterado com Sucesso",  
                type: "success",   
                showConfirmButton: false,
              });
              window.setTimeout(function(){ 
                swal.close();
                var table = $('#tabela').DataTable(); 
                table.ajax.reload( null, false );
              } ,2500);
            }else{
              swal({   
                title: "Error",  
                type: "error",   
                showConfirmButton: false,
              });
              window.setTimeout(function(){
                swal.close();
              } ,2500);
            }
          }
        });
        return false;
      });
    });
     // ativar usuarios
     $(document).on( "click",".btnativar", function() {
      var id_user = $(this).attr("id_user");
      var name = $(this).attr("nome_user");
      swal({   
        title: "Ativar",   
        text: "Ativar: "+name+" ?",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Ativar",   
        closeOnConfirm: true}).then(function(){   
          $.ajax({
          type: "GET",
          url: "<?php echo AJAX; ?>empresas.php",
          data: {'acao':'ativar', 'id': id_user},
          dataType: 'json',
          success: function(res) {
            if(res.status == 200){
              swal({   
                title: "Alterado com Sucesso",  
                type: "success",   
                showConfirmButton: false,
              });
              window.setTimeout(function(){ 
                swal.close();
                var table = $('#tabela').DataTable(); 
                table.ajax.reload( null, false );
              } ,2500);
            }else{
              swal({   
                title: "Error",  
                type: "error",   
                showConfirmButton: false,
              });
              window.setTimeout(function(){
                swal.close();
              } ,2500);
            }
          }
        });
        return false;
      });
    });


 });  
</script>


<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Empresas</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        <h5>Gerenciar Empresas</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb20 col-sm-12 text-center">
                          <button type="submit" class="btn btn-raised  btn-success" id="btnadd" name="btnadd"><i class="fa fa-plus"></i> Adicionar Empresa</button>
                        </div>
                        <div class="col-md-6">
                            <div id="new-search-area"></div>
                          </div>
                        <table id="tabela" class="table table-striped table-bordered table-hover">
                          <thead>
                            <tr class="tableheader">
                              <th>Nome</th>
                              <th>CNPJ</th>
                              <th>Endereço</th>
                              <th>Data Cadastro</th>
                              <th width="17%">Ações</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- resultado -->
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

  <!-- /.content-wrapper -->
<div id="modal_add" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Adicionar</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <!--modal header-->
        <div class="modal-body">
          <form role="form" id="formCadastrar" autocomplete="off">
            <div class="form-group">
              <label>Nome</label>
              <input type="text" class="form-control" name="nome" id="nome">
            </div>
            <div class="form-group">
              <label>CNPJ</label>
              <input type="text" class="form-control cnpj" name="cnpj" id="cnpj">
            </div>
            <div class="form-group">
              <label>Endereço</label>
              <input type="text" class="form-control" name="endereco" id="endereco">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary float-left">Cadastrar</button>
            </div>
           </form>
        </div>
          <!--modal footer-->
        </div>
        <!--modal-content-->
      </div>
      <!--modal-dialog modal-lg-->
    </div>


  <div id="moda_edit" class="modal fade">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Editar</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <!--modal header-->
          <form role="form" id="formCadastrarEdit" autocomplete="off">
        <div class="modal-body">
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="nome" id="nomeEdit">
          </div>
          <div class="form-group">
            <label>CNPJ</label>
            <input type="text" class="form-control cnpj" name="cnpj" id="cnpjEdit">
          </div>
          <div class="form-group">
            <label>Endereço</label>
            <input type="text" class="form-control" name="endereco" id="enderecoEdit">
          </div> 
        <input type="hidden" name="idObj" id="idObj" value="">
        <div class="modal-footer">
          <button type="button" class="btn btn-raised btn-default" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-raised btn-primary">Alterar</button>
        </div>
      </form>
          </div>
          <!--modal footer-->
        </div>
        <!--modal-content-->
      </div>
      <!--modal-dialog modal-lg-->
    </div>
    <!--form-kantor-modal-->