<h2>Your repositories</h2>
<p><button onclick="document.location.href='/repositories/add'">Add repository</button></p>

<?php if(empty($repositories)): ?>
<p>No repositories YET. <a href="/repositories/add">Go create one now!</a></p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Last testdrive</th>
            <th>Result</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($repositories as $r): ?>
        <tr>
            <td><?php echo $r['Repository']['name']; ?></td>
            <td><?php echo (!empty($r['Testdrive']) ? $r['Testdrive'][0]['created'] : 'n/a' ); ?></td>
            <td><?php echo (!empty($r['Testdrive']) ? $r['Testdrive'][0]['result'] : 'n/a' ); ?></td>
            <td><?php echo $this->Html->link('Run tests', $this->Html->url(array('controller' => 'Repositories', 'action' => 'test', $r['Repository']['id']))); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>