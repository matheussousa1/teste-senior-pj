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
   //mascara digito 9
  var maskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
  },
  options = { onKeyPress: function(val, e, field, options) {
      field.mask(maskBehavior.apply({}, arguments), options);
    }
  };

  $(".empresas").select2({
      tags: true,
      tokenSeparators: [',', ' ']
  });

  $('.contato').mask(maskBehavior, options);

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
      "url": "<?php echo AJAX; ?>usuarios.php?acao=buscar",
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
      { "data": "email" },
      { "data": "telefone" },
      { "data": "dataNascimento" },
      { "data": "cidade" },
      { "data": "empresas" },
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
      email : { required: true},
    },
    messages: {
      nome : { required: 'Preencha este campo' },
      email : { required: 'Preencha este campo'},
    },
    submitHandler: function( form ){
      var dados = $('#formCadastrar').serialize();
      $.ajax({
        type: "GET",
        url: "<?php echo AJAX; ?>usuarios.php?acao=cadastrar",
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
        url : "<?php echo AJAX; ?>usuarios.php?acao=buscarDados",
        type: "GET",
        data : value,
        success: function(data, textStatus, jqXHR){
          var data = jQuery.parseJSON(data);
          $("#nomeEdit").val(data.nome);
          $("#emailEdit").val(data.email);
          $("#telefoneEdit").val(data.telefone);
          $("#dataNascimentoEdit").val(data.dataNascimento);
          $("#cidadeEdit").val(data.cidade);
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
        email : { required: true},
      },
      messages: {
        nome : { required: 'Preencha este campo' },
        email : { required: 'Preencha este campo'},
      },
      submitHandler: function( form ){
        var dados = $('#formCadastrarEdit').serialize();
        $.ajax({
          type: "GET",
          url: "<?php echo AJAX; ?>usuarios.php?acao=editar",
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
          url: "<?php echo AJAX; ?>usuarios.php",
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
          url: "<?php echo AJAX; ?>usuarios.php",
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

    // adicionar unser
    $(document).on("click",".btnempresas",function(){
      var id = $(this).attr("id_user");
      $("#empresas").html('');
      $.ajax({
        url: '<?php echo AJAX; ?>usuarios.php?acao=buscarEmpresas',
        type: 'GET',
        dataType: 'json',
        data: {id: id},
      })
      .done(function(res) {
        $("#modal_empresa").modal("show");
        $("#idObjEmpresa").val(id);
        var selected = '';
        $.each(res,function(key, value){
          if(value.selected == 1){ 
            selected = 'selected';   
          }else{
            selected = '';   
          }

          $("#empresas").append('<option value=' + value.id + ' '+selected+' >' + value.nome + '</option>'); // return empty
        });
      });
      
    });


    $('#formCadastrarEmpresas').validate({
      submitHandler: function( form ){
        var dados = $('#formCadastrarEmpresas').serialize();
        $.ajax({
          type: "GET",
          url: "<?php echo AJAX; ?>usuarios.php?acao=cadastrarEmpresas",
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
                $('#formCadastrarEmpresas input').val(""); 
                swal.close();
                var table = $('#tabela').DataTable(); 
                table.ajax.reload( null, false );
                $("#modal_empresa").modal("hide");
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
                            <h5 class="m-b-10">Usuários</h5>
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
                        <h5>Gerenciar Usuários</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb20 col-sm-12 text-center">
                          <button type="submit" class="btn btn-raised  btn-success" id="btnadd" name="btnadd"><i class="fa fa-plus"></i> Adicionar Usuário</button>
                        </div>
                        <div class="col-md-6">
                            <div id="new-search-area"></div>
                          </div>
                        <table id="tabela" class="table table-striped table-bordered table-hover">
                          <thead>
                            <tr class="tableheader">
                              <th>Nome</th>
                              <th>Email</th>
                              <th>Telefone</th>
                              <th>Data Nascimento</th>
                              <th>Cidade</th>
                              <th>Empresas</th>
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
              <label>Email</label>
              <input type="email" class="form-control" name="email" id="email">
            </div>
            <div class="form-group">
              <label>Telefone</label>
              <input type="text" class="form-control contato" name="telefone" id="telefone">
            </div>
            <div class="form-group">
              <label>Data Nascimento</label>
              <input type="date" class="form-control" name="dataNascimento" id="dataNascimento">
            </div>
            <div class="form-group">
              <label>Cidade</label>
              <input type="text" class="form-control" name="cidade" id="cidade">
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
            <label>Email</label>
            <input type="email" class="form-control" name="email" id="emailEdit">
          </div>
          <div class="form-group">
            <label>Telefone</label>
            <input type="text" class="form-control contato" name="telefone" id="telefoneEdit">
          </div>
          <div class="form-group">
            <label>Data Nascimento</label>
            <input type="date" class="form-control" name="dataNascimento" id="dataNascimentoEdit">
          </div>
          <div class="form-group">
            <label>Cidade</label>
            <input type="text" class="form-control" name="cidade" id="cidadeEdit">
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

<!-- modal alterar senha-->
<div id="modal_empresa" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Selecione Empresas</h4>
            <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
      <!--modal header-->
      <form role="form" id="formCadastrarEmpresas" autocomplete="off">
        <div class="modal-body">
          <div class="form-group">
            <h5>Selecione Empresas</h5>
            <select class="empresas col-sm-12" multiple="multiple" id="empresas" name="empresas[]" style="width: 100%">
            </select>
          </div> 
        </div>
        <input type="hidden" name="idObj" id="idObjEmpresa" value="">
        <div class="modal-footer">
          <button type="button" class="btn btn-raised  btn-default" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-raised  btn-primary hide" id="bntAlterarSenha">Cadastrar</button>
        </div>
      </form>
    </div>
    <!--modal footer-->
    </div>
<!--modal-content-->
</div>
