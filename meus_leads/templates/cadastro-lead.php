<div class="wrap">
    <h1>Cadastrar Novo Lead</h1>
    <br/>

    <?php if ( isset( $_GET['erro'] ) && ! empty( $_GET['erro'] ) ) : ?>
        <div class="notice notice-error">
            <p>Erro ao salvar o lead. Por favor, tente novamente.</p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="cadastrar-lead">

        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="nome">Nome</label></th>
                    <td><input type="text" name="nome" id="nome" required></td>
                </tr>
                <tr>
                    <th><label for="email">E-mail</label></th>
                    <td><input type="email" name="email" id="email" required></td>
                </tr>
                <tr>
                    <th><label for="whatsapp">WhatsApp</label></th>
                    <td><input type="text" name="whatsapp" id="whatsapp"></td>
                </tr>
            </tbody>
        </table>

        <?php submit_button( 'Salvar' ); ?>
    </form>
</div>