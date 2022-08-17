<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
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
    
    $('.cpf').mask('999.999.999-99');
    $('.cep').mask('99999-999');

    $('#salario').maskMoney({thousands:'', decimal:'.'});
    
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
          "url": "<?php echo AJAX; ?>colaborador.php?acao=buscar",
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
            { "data": "cpf"},
            { "data": "rg"},
            { "data": "dataNascimento"},
            { "data": "cep"},
            { "data": "endereco"},
            { "data": "numero"},
            { "data": "cidade"},
            { "data": "estado"},
            { "data": "dataCadastro"},
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
          cpf : { required: true},
        },
        messages: {
          nome : { required: 'Preencha este campo' },
          cpf : { required: 'Preencha este campo'},
        },
        submitHandler: function( form ){
          var dados = $('#formCadastrar').serialize();
          $.ajax({
            type: "GET",
            url: "<?php echo AJAX; ?>colaborador.php?acao=cadastrar",
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


    // buscar CEP
    $('.cep').blur(function(event) {
        var cep = $(this).val().replace(/[^\d]+/g,'');
        if(cep.length == 8){
            $("#carregandoCep").removeClass("hide");
            $.ajax({
                type: "GET",
                url: 'https://viacep.com.br/ws/'+cep+'/json/',
                dataType: 'json',
                success: function(dados) {
                    $("#carregandoCep").addClass("hide");
                    if(dados.erro == true){
                        swal({   
                            title: "CEP não encontrado",  
                            type: "error",   
                            showConfirmButton: true,
                        });
                        $('#cep').val('');
                    }else{
                        $('#endereco').val(dados.logradouro);
                        $("#cidade" ).val(dados.localidade);
                        $("#estado" ).val(dados.uf);
                    }
                }
            }); 
        }else{
            swal({   
                title: "CEP Incompleto",  
                type: "error",   
                showConfirmButton: true,
            });
            $('#cep').val('');
        }
    });

     //abrir modal pra edição
    $(document).on("click",".btnedit",function(){
      var id_user = $(this).attr("id_user");
      var value = {
        id: id_user
      };
      $.ajax({
        url : "<?php echo AJAX; ?>colaborador.php?acao=buscarDados",
        type: "GET",
        data : value,
        success: function(data, textStatus, jqXHR){
            var data = jQuery.parseJSON(data);
            $("#nomeEdit").val(data.nome);
            $("#cpfEdit").val(data.cpf);
            $("#rgEdit").val(data.rg);
            $("#dataNascimentoEdit").val(data.dataNascimento);
            $("#cepEdit").val(data.cep);
            $("#enderecoEdit").val(data.endereco);
            $("#numeroEdit").val(data.numero);
            $("#cidadeEdit").val(data.cidade);
            $("#estadoEdit").val(data.estado);
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
        cpf : { required: true},
      },
      messages: {
        nome : { required: 'Preencha este campo' },
        cpf : { required: 'Preencha este campo'},
      },
      submitHandler: function( form ){
        var dados = $('#formCadastrarEdit').serialize();
        $.ajax({
          type: "GET",
          url: "<?php echo AJAX; ?>colaborador.php?acao=editar",
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
          url: "<?php echo AJAX; ?>colaborador.php",
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
          url: "<?php echo AJAX; ?>colaborador.php",
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

 

    //vincular salarios
    $(document).on("click",".btnhistoricosalario",function(){
      var id_user = $(this).attr("id_user");
      $("#idUsuario").val(id_user);
      buscarHistoricoSalario(id_user);
    });

    $(document).on("click","#btnaddsalario",function(){
        $("#modal_add_salario").modal("show");
        $("#nome").focus();
    });

    $('#formCadastrarSalario').validate({
      rules: {
        salario : { required: true},
      },
      messages: {
        salario : { required: 'Preencha este campo' },
      },
      submitHandler: function( form ){
        var dados = $('#formCadastrarSalario').serialize();
        $.ajax({
          type: "GET",
          url: "<?php echo AJAX; ?>colaborador.php?acao=cadastrarSalario",
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
                   $('#formCadastrarSalario input').val(""); 
                   swal.close();
                    var table = $('#tabelaSalarios').DataTable(); 
                    table.ajax.reload( null, false );
                    $("#modal_add_salario").modal("hide");
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

//vincular unidades
function buscarHistoricoSalario(id) {

  var idUser = id;

  $('#tabelaSalarios').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": false,
    "responsive": true,
    "autoWidth": false,
    "pageLength": 10,
    "destroy": true,
    "ajax": {
        "url": "<?php echo AJAX; ?>colaborador.php?acao=buscarHistoricoSalario&idUser="+idUser,
        "type": "GET"
    },
    "language": {
      "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
    },
    "createdRow": function ( row, data, index ) {
      console.log(data['ativo']);
        if(data['ativo'] == 0){
          $(row).addClass('danger');
        }
      },
    "columns": [
      { "data": "salario" }
    ]
  });
  $("#modal_vincular_salarios").modal('show');
  }
</script>


<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Colaborador</h5>
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
                        <h5>Gerenciar Colaborador</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb20 col-sm-12 text-center">
                          <button type="submit" class="btn btn-raised  btn-success" id="btnadd" name="btnadd"><i class="fa fa-plus"></i> Adicionar Colaborador</button>
                        </div>
                        <div class="col-md-6">
                            <div id="new-search-area"></div>
                          </div>
                        <table id="tabela" class="table table-striped table-bordered table-hover">
                          <thead>
                            <tr class="tableheader">
                              <th>Nome</th>
                              <th>CPF</th>
                              <th>RG</th>
                              <th>Data Nascimento</th>
                              <th>CEP</th>
                              <th>Endereço</th>
                              <th>Numero</th>
                              <th>Cidade</th>
                              <th>Estado</th>
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
              <label>CPF</label>
              <input type="text" class="form-control cpf" name="cpf" id="cpf">
            </div>
            <div class="form-group">
              <label>RG</label>
              <input type="text" class="form-control contato" name="rg" id="rg">
            </div>
            <div class="form-group">
              <label>Data Nascimento</label>
              <input type="date" class="form-control" name="dataNascimento" id="dataNascimento">
            </div>
            <div class="form-group">
              <label>CEP</label>
              <input type="text" class="form-control cep" name="cep" id="cep">
            </div>
            <div class="form-group">
              <label>Endereço</label>
              <input type="text" class="form-control" name="endereco" id="endereco">
            </div>
            <div class="form-group">
              <label>Numero</label>
              <input type="text" class="form-control" name="numero" id="numero">
            </div>
            <div class="form-group">
              <label>Cidade</label>
              <input type="text" class="form-control" name="cidade" id="cidade">
            </div>
            <div class="form-group">
              <label>Estado</label>
              <input type="text" class="form-control" name="estado" id="estado">
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
              <label>CPF</label>
              <input type="text" class="form-control cpf" name="cpf" id="cpfEdit">
            </div>
            <div class="form-group">
              <label>RG</label>
              <input type="text" class="form-control contato" name="rg" id="rgEdit">
            </div>
            <div class="form-group">
              <label>Data Nascimento</label>
              <input type="date" class="form-control" name="dataNascimento" id="dataNascimentoEdit">
            </div>
            <div class="form-group">
              <label>CEP</label>
              <input type="text" class="form-control cep" name="cep" id="cepEdit">
            </div>
            <div class="form-group">
              <label>Endereço</label>
              <input type="text" class="form-control" name="endereco" id="enderecoEdit">
            </div>
            <div class="form-group">
              <label>Numero</label>
              <input type="text" class="form-control" name="numero" id="numeroEdit">
            </div>
            <div class="form-group">
              <label>Cidade</label>
              <input type="text" class="form-control" name="cidade" id="cidadeEdit">
            </div>
            <div class="form-group">
              <label>Estado</label>
              <input type="text" class="form-control" name="estado" id="estadoEdit">
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


<div id="modal_vincular_salarios" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Historico Salarios</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <!--modal header-->
      <div class="modal-body">
        <button type="submit" class="btn btn-raised btn-primary mb-3" id="btnaddsalario"><i class="fa fa-plus"></i> Adicionar Salario</button>
        <table id="tabelaSalarios" class="table table-striped table-bordered table-hover">
          <thead>
            <tr class="tableheader">
              <th>Salario</th>
            </tr>
          </thead>
          <tbody>
            <!-- resultado -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-raised btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
      <!--modal footer-->
    </div>
    <!--modal-content-->
  </div>

  <div id="modal_add_salario" class="modal fade">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Adicionar Salario</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <!--modal header-->
      <div class="modal-body">
        <form role="form" id="formCadastrarSalario" autocomplete="off">
            <div class="form-group">
                <label>Salario</label>
                <input type="text" class="form-control" name="salario" id="salario">
            </div>
            <input type="hidden" name="idUsuario" id="idUsuario" value="">
          <div class="modal-footer">
            <button type="button" class="btn btn-raised  btn-default" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-raised  btn-primary">Adicionar</button>
          </div>
         </form>
      </div>
        <!--modal footer-->
      </div>
      <!--modal-content-->
    </div>
    <!--modal-dialog modal-lg-->
  </div>
