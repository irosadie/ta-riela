<ul class="sidebar-menu">
    <li class="menu-header">
        <Menu></Menu>
    </li>

    <li class="nav-item">
        <a href=<?= Yii::getAlias('@web') ?> class="nav-link"><i class="fas fa-home"></i><span>Beranda</span></a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i><span>Data Riset</span></a>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link" href=<?= Yii::getAlias('@web/data/index') ?>>Data (Sekunder)</a>
            </li>
            <li>
                <a class="nav-link" href=<?= Yii::getAlias('@web/data/split') ?>>Pembagian Data</a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-cogs"></i><span>Pembobotan</span></a>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link" href=<?= Yii::getAlias('@web/weight?type=tfidf') ?>>TF.IDF</a>
            </li>
            <li>
                <a class="nav-link" href=<?= Yii::getAlias('@web/weight?type=tfabs') ?>>TF.ABS</a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-list"></i><span>SVM</span></a>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link" href=<?= Yii::getAlias('@web/train') ?>>Pelatihan</a>
            </li>
            <li>
                <a class="nav-link" href=<?= Yii::getAlias('@web/test') ?>>Pengujian</a>
            </li>
        </ul>
    </li>
</ul>