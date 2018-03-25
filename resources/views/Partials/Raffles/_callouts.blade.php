<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-bars" aria-hidden="true"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">No. of Raffles</span>
            <span class="info-box-number">{{ $stats['total_number_of_raffles'] }}</span>
        </div>
    </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-user-plus" aria-hidden="true"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">No. of Sign ups</span>
            <span class="info-box-number">{{ $stats['total_number_of_signups'] }}</span>
        </div>
    </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-blue"><i class="fa fa-facebook" aria-hidden="true"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Facebook Share</span>
            <span class="info-box-number">{{ $stats['total_number_of_fb_share'] }}</span>
        </div>
    </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-usd" aria-hidden="true"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Prizes Given</span>
            <span class="info-box-number">{{ number_format($stats['total_number_of_prize_given'], 2, '.', ',') }}</span>
        </div>
    </div>
</div>