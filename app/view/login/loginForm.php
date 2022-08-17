<!-- [ signin-img ] start -->
<div class="auth-wrapper align-items-stretch aut-bg-img">
  <div class="flex-grow-1">
    <div class="h-100 d-md-flex align-items-center auth-side-img">
      <div class="col-sm-10 auth-content w-auto">
        <img src="assets/images/auth/logo_teste.png" alt="" class="img-fluid logo-login">
        <h1 class="text-white my-4">Bem vindo!</h1>
        <h4 class="text-white font-weight-normal">Processo seletivo Contato Seguro Programador Sênior </br> Candidato: Matheus Ferreira Sousa</h4>
      </div>
    </div>
    <div class="auth-side-form">
      <form action="<?php echo SITE."login/logar";?>" method="post">
        <div class=" auth-content">
          <img src="assets/images/auth/cs_logo.png" alt="" class="img-fluid mb-4 d-block d-xl-none d-lg-none">
          <h3 class="mb-4 f-w-400">Efetuar Login</h3>
          <div class="form-group mb-3">
            <label class="floating-label" for="Email">Email</label>
            <input type="email" class="form-control" name="idUser" id="idUser" required="required" placeholder="Usuário" value="teste@email.com">
          </div>
          <div class="form-group mb-4">
            <label class="floating-label" for="Password">Senha</label>
            <input type="password" class="form-control" name="senha" id="senha" required="required" placeholder="Senha" value="123456">
          </div>
          <div class="custom-control custom-checkbox text-left mb-4 mt-2">
            <input type="checkbox" class="custom-control-input" id="customCheck1">
            <label class="custom-control-label" for="customCheck1">Salvar credênciais.</label>
          </div>
          <button class="btn btn-block btn-primary mb-4" type="submit">Entrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- [ signin-img ] end -->