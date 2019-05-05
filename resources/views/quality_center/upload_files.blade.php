<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<div class="container">
    <div class="col-md-8 offset-md-2">
        <h4 >
            学习质量数据分析与操作
        </h4>
        @include('shared._errors')
        @include('shared._messages')
        <form action="{{ route('upload') }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data"
              autocomplete="off">

        @csrf <!-- {{ csrf_field() }} -->
            <div class="form-group">
                <label for="name-field">任务名</label>
                <input class="form-control" type="text" name="task_name" id="name-field" value=""/>
            </div>

            <div class="form-group mb-4">
                <label for="" class="avatar-label">试题评分数据表:</label>
                <input type="file" name="ed" class="form-control-file">
            </div>
            <div class="form-group mb-4">
                <label for="" class="avatar-label">双向细目表:</label>
                <input type="file" name="sxxmb" class="form-control-file">
            </div>
            <div class="form-group mb-4">
                <label for="" class="avatar-label">问卷定义表:</label>
                <input type="file" name="wjb" class="form-control-file">
            </div>
            <div class="form-group mb-4">
                <label for="" class="avatar-label">调查问卷数据表:</label>
                <input type="file" name="ad" class="form-control-file">
            </div>

            <div class="well well-sm">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>
        </form>
    </div>

    <div class="col-md-8 offset-md-2">
        <h4>
            <i class="glyphicon glyphicon-edit"></i> 数据文件格式如下：
        </h4>

        <ul>
            <li><a href="../../../../uploads/example/SXXMB.csv">双向细目表</a></li>
            <li><a href="../../../../uploads/example/ED.csv">试题评分数据表</a></li>
            <li><a href="../../../../uploads/example/WJB.csv">问卷定义表</a></li>
            <li><a href="../../../../uploads/example/AD.csv">调查问卷数据表</a></li>

        </ul>
    <div>
</div>

