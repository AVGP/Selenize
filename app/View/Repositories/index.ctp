<h2>Your repositories</h2>
<p><button onclick="document.location.href='/repositories/add'">Add repository</button></p>

<?php if(empty($repositories)): ?>
<p>No repositories YET. <a href="/repositories/add">Go create one now!</a></p>
<?php else: ?>
<table class="row-fluid">
    <thead>
        <tr>
            <th class="left-aligned">Name</th>
            <th class="left-aligned">Last testdrive</th>
            <th class="left-aligned">Result</th>
            <th class="left-aligned">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php 
        foreach($repositories as $r):
        if(!empty($r['Testdrive'])) {
            switch($r['Testdrive'][0]['result']) {
                case 'Success':
                    $labelClass = 'label-success';
                break;
                case 'Failure':
                    $labelClass = 'label-important';
                break;
                case 'Aborted':
                    $labelClass = 'label-warning';
                break;                    
                default:
                    $labelClass = 'label-info';
                break;
            }
        }
        else $labelClass = '';
    ?>
        <tr>
            <td class="left-aligned"><?php echo $r['Repository']['name']; ?></td>
            <td class="left-aligned"><?php echo (!empty($r['Testdrive']) ? $r['Testdrive'][0]['created'] : 'n/a' ); ?></td>
            <td class="left-aligned"><span class="label <?php echo $labelClass; ?>"><?php echo (!empty($r['Testdrive']) ? $r['Testdrive'][0]['result'] : 'n/a' ); ?></span></td>
            <td class="left-aligned">
                <?php echo $this->Html->link('Run tests', $this->Html->url(array('controller' => 'Repositories', 'action' => 'test', $r['Repository']['id'])), array('class' => 'btn')); ?>
                <?php echo $this->Html->link('Show history', $this->Html->url(array('controller' => 'Repositories', 'action' => 'show', $r['Repository']['id'])), array('class' => 'btn')); ?>                    
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>