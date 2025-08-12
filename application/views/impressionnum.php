<div class="container">
    <table class="table mt-5 border">
        <?php if (isset($code)) : ?>
            <tbody>
                <?php for ($i = 0; $i < count($code); $i++) : ?>
                    <td>
                        <a href="<?= base_url('CodeBarre/creatCode/' . $code[$i]->numero  ) ?>" download>
                            <img src="<?= base_url('CodeBarre/creatCode/' . $code[$i]->numero ) ?>" alt="">
                        </a>
                    </td>
                <?php endfor; ?>
            </tbody>
        <?php endif; ?>
    </table>
</div>