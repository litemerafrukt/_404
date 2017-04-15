<div class="row">
    <div class="calendar-navigation">
        <a class="btn" href="<?= $prevMonthPath ?>">« <?= $prevMonthName ?></a>
        <h3><?= $currentMonthName ?></h3>
        <a class="btn" href="<?= $nextMonthPath ?>"><?= $nextMonthName ?> »</a>
    </div>
</div>

<div class="row">
    <img class="img-responsive" src="<?= $image ?>" alt="month image">
</div>

<div class="row">
    <div class="calendar-table">
        <div class="calendar-head">
            <div class="calendar-weekday">Mån<span class="calendar-hide-small">dag</span></div>
            <div class="calendar-weekday">Tis<span class="calendar-hide-small">dag</span></div>
            <div class="calendar-weekday">Ons<span class="calendar-hide-small">dag</span></div>
            <div class="calendar-weekday">Tors<span class="calendar-hide-small">dag</span></div>
            <div class="calendar-weekday">Fre<span class="calendar-hide-small">dag</span></div>
            <div class="calendar-weekday">Lör<span class="calendar-hide-small">dag</span></div>
            <div class="calendar-weekday calendar-holiday">Sön<span class="calendar-hide-small">dag</span></div>
        </div>

        <?php foreach ($weeks as $weekdays) : ?>
            <div class="calendar-week">
                <?php foreach ($weekdays as $day) : ?>
                    <div class="
                        calendar-cell
                        <?= $day->active ? '' : 'calendar-mute' ?>
                        <?= $day->isToday() ? 'calendar-today' : '' ?>
                    ">
                        <p class="calendar-number <?= $day->isHoliday() ? 'calendar-holiday' : ''?>">
                            <?= $day->number() ?>
                        </p>

                        <p class="calendar-holiday-name calendar-hide-small">
                            <?= $day->holidayName() ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>
