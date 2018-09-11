<?php
    require(__DIR__ . '/src/config/config.php');

    if(!file_exists('data.json')) {
        die('Запустите обновления файлом cron.php');
    }

    if( isset($_GET['key']) AND $_GET['key'] === SECRET_KEY ) {
        $data = json_decode(file_get_contents(__DIR__ . '/data.json'), true);

        ?>
            <h1>Мониторинг хостинг-аккаунтов</h1>

            <p>Дата последнего обновления: <?php echo $data['updated_at']; ?></p>
            <table cellspacing="2" border="1" cellpadding="5" width="600">
                <?php foreach($data['result'] as $item) { ?>
                    <tr>
                        <td><?php echo $item['alias'] ?></td>
                        <td><?php echo $item['output'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php
    } ?>