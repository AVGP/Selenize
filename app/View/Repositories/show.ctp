<h2><?php echo $repository['Repository']['name']; ?></h2>
<div style="padding-bottom: 10px;"><a href="/repositories" class="btn btn-primary">Back to repositories</a></div>
<table class="row-fluid centered bordered-table">
    <thead>
        <tr class="row-fluid">
            <th class="span3 left-aligned">Date</th>
            <th class="span3 left-aligned">Result</th>
            <th class="span2 left-aligned">Details</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($repository['Testdrive'] as $testdrive): ?>
        <tr class="row-fluid <?php echo ( $testdrive['result'] === 'Success' ? 'good' : 'bad' ); ?>">
            <td class="span3 left-aligned"><?php echo $testdrive['created']; ?></td>
            <td class="span3 left-aligned"><span class="label label-<?php echo ($testdrive['result'] === 'Success' ? 'success' : ($testdrive['result'] === 'Failure' ? 'important' : 'warning')); ?>"><?php echo $testdrive['result']; ?></span></td>
            <td class="span2 left-aligned">
                <?php echo $this->Html->link('Show log', '#log_' . $testdrive['id'], array('class' => 'btn' , 'data-toggle' => 'modal')); ?>
                <div class="modal hide fade" id="log_<?php echo $testdrive['id']; ?>">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">×</button>                        
                        <h3>Log output</h3>
                    </div>
                    <div class="modal-body">
                        <pre><?php echo $testdrive['logtext']; ?></pre>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>                        
                    </div>
                </div>        
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div style="padding-top: 10px;"><a href="/repositories" class="btn btn-primary">Back to repositories</a></div>