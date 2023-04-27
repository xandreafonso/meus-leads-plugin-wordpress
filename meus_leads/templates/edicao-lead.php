<div class="wrap">
    <h1>Editar Lead</h1>

    <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">

        <input type="hidden" name="action" value="atualizar-lead">
        <input type="hidden" name="id" value="<?php echo $lead->id; ?>">

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="lead_nome">Nome</label>
                    </th>
                    <td>
                        <input name="nome" type="text" id="lead_nome" value="<?php echo esc_attr( $lead->nome ); ?>" class="regular-text" required>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="lead_email">Email</label>
                    </th>
                    <td>
                        <input name="email" type="email" id="lead_email" value="<?php echo esc_attr( $lead->email ); ?>" class="regular-text" required>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="lead_whatsapp">WhatsApp</label>
                    </th>
                    <td>
                        <input name="whatsapp" type="text" id="lead_whatsapp" value="<?php echo esc_attr( $lead->whatsapp ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="lead_origem">Origem</label>
                    </th>
                    <td>
                        <input name="origem" type="text" id="lead_origem" value="<?php echo esc_attr( $lead->origem ); ?>" class="regular-text">
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button('Salvar Alterações'); ?>
    </form>
</div>