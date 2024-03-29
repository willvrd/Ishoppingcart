<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">{{ trans('ishoppingcart::post.latest posts') }}</h3>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-striped">
            <tbody><tr>
                <th style="width: 10px">#</th>
                <th>{{ trans('ishoppingcart::post.table.title') }}</th>
                <th>{{ trans('core::core.table.created at') }}</th>
            </tr>
            <?php if (isset($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->created_at }}</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div>
