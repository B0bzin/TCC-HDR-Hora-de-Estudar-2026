<?php
/**
 * Área de comentários (usada nos vídeos).
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Não mostra nada se a senha do post for exigida
if ( post_password_required() ) return;
?>

<div class="epa-comentarios">
    <h3 class="mb-4">
        <i class="fa fa-comments me-2" style="color:#1A77D2;"></i>
        <?php
        $total = get_comments_number();
        if ( $total == 0 )      echo 'Comentários';
        elseif ( $total == 1 )  echo '1 comentário';
        else                    echo esc_html( $total ) . ' comentários';
        ?>
    </h3>

    <?php if ( have_comments() ) : ?>
        <ol class="epa-lista-comentarios">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'avatar_size' => 44,
                'short_ping'  => true,
            ) );
            ?>
        </ol>

        <?php the_comments_pagination( array(
            'prev_text' => '‹ Anteriores',
            'next_text' => 'Próximos ›',
        ) ); ?>
    <?php else : ?>
        <p class="epa-sem-comentarios">Seja o primeiro a comentar sobre este vídeo!</p>
    <?php endif; ?>

    <?php
    if ( comments_open() ) {
        comment_form( array(
            'title_reply'          => 'Deixe seu comentário',
            'title_reply_before'   => '<h4 class="epa-form-titulo">',
            'title_reply_after'    => '</h4>',
            'label_submit'         => 'Publicar comentário',
            'class_submit'         => 'btn btnDefault py-2 px-4',
            'comment_notes_before' => '<p class="epa-nota-coment">Seu e-mail não será publicado.</p>',
            'comment_field'        => '<div class="mb-3"><label for="comment" class="epa-label-coment">Comentário</label>'
                . '<textarea id="comment" name="comment" class="form-control formDefault" rows="4" required placeholder="Escreva o que achou do vídeo..."></textarea></div>',
        ) );
    } else {
        echo '<p class="epa-sem-comentarios">Os comentários estão fechados.</p>';
    }
    ?>
</div>
