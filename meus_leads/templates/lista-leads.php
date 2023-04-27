<div class="wrap">
    <h1 class="wp-heading-inline">Meus Leads</h1>
    <br/><br/>

    <a href="<?php echo admin_url( 'admin.php?page=cadastro-lead' ); ?>" class="page-title-action">Adicionar Novo Lead</a>
    <a href="<?php echo admin_url( 'admin-post.php?action=download-leads' ); ?>" class="page-title-action">Exportar Base Completa (CSV)</a>
    <br/><br/>

    <form method="get" action="<?php echo admin_url( 'admin.php' ); ?>">
        <input type="hidden" name="page" value="meus-leads" />
        <input type="text" name="pesquisa" value="<?php echo $pesquisa ?>" placeholder="Pesquisa por nome, email e origem" style="width: 300px" />
        <button type="submit">Pesquisar</button>
    </form>
    <br/>

    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">WhatsApp</th>
                <th scope="col">Origem</th>
                <th scope="col">Cadastro</th>
                <th scope="col">Atualização</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $leads as $lead ) { ?>
                <tr>
                    <td><?php echo $lead->id; ?></td>
                    <td><?php echo $lead->nome; ?></td>
                    <td><?php echo $lead->email; ?></td>
                    <td><?php echo $lead->whatsapp; ?></td>
                    <td><?php echo $lead->origem; ?></td>
                    <td><?php echo $lead->data_cadastro; ?></td>
                    <td><?php echo $lead->data_atualizacao; ?></td>
                    <td><a href="<?php echo admin_url( 'admin.php?page=edicao-lead&id=' . $lead->id ); ?>">Editar</a></td>
                    <td>
                        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
                            <input type="hidden" name="action" value="remover-lead">
                            <input type="hidden" name="id" value="<?php echo $lead->id; ?>">
                            <button type="submit" onclick="return confirm('Tem certeza de que deseja excluir este lead?')">Remover</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <p>
        <?php echo $quantidade_por_pag ?> por página | Página <?php echo $pagina ?> de <?php echo $quantidade_paginas ?>: 
        <a href="<?php echo admin_url( 'admin.php?page=meus-leads&pagina=1'. (isset($pesquisa) ? "&pesquisa=$pesquisa" : '') ); ?>">Primeira Pág.</a>
         | 
        <a href="<?php echo admin_url( 'admin.php?page=meus-leads&pagina=' . ($pagina - 1) . (isset($pesquisa) ? "&pesquisa=$pesquisa" : '')  ); ?>">Pág. Anterior</a>
         | 
        <a href="<?php echo admin_url( 'admin.php?page=meus-leads&pagina=' . ($pagina + 1) . (isset($pesquisa) ? "&pesquisa=$pesquisa" : '')  ); ?>">Próxima Pág.</a>
         | 
        <a href="<?php echo admin_url( 'admin.php?page=meus-leads&pagina=' . $quantidade_paginas . (isset($pesquisa) ? "&pesquisa=$pesquisa" : '')  ); ?>">Última Pág.</a>
    </p>
</div>