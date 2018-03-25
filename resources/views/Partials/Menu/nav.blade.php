<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                {{--<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>--}}
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Raffles <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        {{--<li><a href="javascript:void(0)" id="toggle-create-raffle" >Create New Raffle</a></li>--}}
                        {{--<li role="separator" class="divider"></li>--}}
                        <li><a href="{{ url('/') }}">Raffles</a></li>
                        <li><a href="{{ url('/raffle/winners') }}">Winners</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/prizes/list') }}">Prizes</a></li>
                <li><a href="{{ url('/actions/list') }}">Actions</a></li>
            </ul>
            {{--<form class="navbar-form navbar-left">--}}
                {{--<div class="form-group">--}}
                    {{--<input type="text" class="form-control" placeholder="Search">--}}
                {{--</div>--}}
                {{--<button type="submit" class="btn btn-default">Submit</button>--}}
            {{--</form>--}}
            <ul class="nav navbar-nav navbar-right">
                <li><a href="javascript:void(0)" id="toggle-logout">Logout</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>