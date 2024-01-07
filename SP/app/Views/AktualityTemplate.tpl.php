<?php
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require("app/Views/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

?>
<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Vypis obsahu sablony -->
<?php


if($tplData['prihlasen']){
    $tplHeaders->getHTMLHeaderPrihlasen($tplData['title'],$tplData['id_pravo']);
}else{
    $tplHeaders->getHTMLHeader($tplData['title']);
}
?>
<div class="container-fluid lingrad">
    <h2 class="py-3 font-weight-bold text-primary">Aktuality konference</h2>

    <div class="row py-3">
           <div class="card-group">
               <!--Dopln hlavu do tela nekam ukaz víc a carousel muzes pridat-->
               <div class="container col-xl-4 col-lg-6 col-md-12 col-sm-12 py-3">
                   <div class="card border-dark">
                       <div class="card-header">
                           <div class="row container">
                               <h4 class="font-weight-bold font-italic">
                                   Integer quis
                                   <sup class="badge badge-danger badge-pill">
                                       <span class="spinner-grow text-light"></span>
                                       Nově
                                   </sup>
                               </h4>
                           </div>
                           <div class="text-right">11.11.2023</div>
                       </div>
                       <div class="card-body bg-success">
                           <div class="col-12 text-justify text-dark">
                               <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices urna at quam condimentum luctus. Sed sagittis viverra ultrices. Integer quis diam massa. Nunc sodales, est eu varius lobortis, nisi dolor feugiat sapien, non posuere erat tortor quis ipsum. Nam leo nibh, semper vel dui vel, auctor molestie leo. Nulla sapien nulla, pharetra in varius eget, imperdiet sit amet sem. Curabitur sagittis id tortor eu mattis. Pellentesque nec maximus mauris, sed aliquam nisl. Quisque lobortis, lorem sed convallis volutpat, sem urna lobortis urna, nec faucibus dolor libero posuere ante. Quisque at magna augue. Quisque velit nisi, scelerisque at eros eu, vulputate ullamcorper diam. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin vehicula nulla nec molestie vestibulum. Aenean quis mauris rhoncus, pellentesque diam sed, efficitur massa.
                               </p>
                           </div>
                           <div>
                               <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#info">Zjistit více</button>
                               <div class="text-justify col-12 text-light bg-success rounded collapse" id="info" >
                                   Integer efficitur est vitae arcu pellentesque, nec egestas arcu dignissim. In et nulla metus. Vivamus sollicitudin eros ullamcorper turpis dignissim eleifend. Nam iaculis iaculis viverra. Cras velit lectus, pretium eu nisi eu, accumsan lacinia leo. Etiam velit nunc, tempus ac eleifend suscipit, tristique ut mauris.
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="container col-xl-4 col-lg-6 col-md-12 col-sm-12 py-3">
                   <div class="card border-dark">
                       <div class="card-header">
                           <h4>
                               Integer eleifend
                           </h4>
                           <div class="text-right">8.9.2023</div>
                       </div>
                       <div class="card-body bg-warning">
                           <div class="col-12 text-justify">
                               <p>
                                   Integer id tortor elementum ante mollis condimentum. In condimentum mi quis dui convallis fermentum. In vestibulum erat vel lacus porttitor aliquet. Morbi at eros massa. Integer eleifend lacinia ex eget feugiat. Ut sem justo, cursus non condimentum nec, blandit sed nisl. Nulla facilisi. Ut viverra arcu ac gravida vulputate. Suspendisse accumsan varius mi, quis efficitur sem ultrices vel. Maecenas non bibendum nunc. Integer nec turpis sit amet dolor pretium tincidunt semper quis nulla. Nam rhoncus mauris vel justo finibus cursus. Nulla tincidunt pellentesque vehicula. Curabitur in purus at nisl lacinia vehicula.
                               </p>
                           </div>
                           <div>
                               <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#info2">Zjistit více</button>
                               <div class="text-justify col-12 text-light bg-warning rounded collapse" id="info2" >
                                   Fusce sit amet dictum massa. Duis posuere ipsum nunc, non pretium lorem vulputate non. Nullam facilisis ac magna in volutpat. In hac habitasse platea dictumst.
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="container col-xl-4 col-lg-12 col-md-12 col-sm-12 py-3">
                   <div class="card border-dark">
                       <div class="card-header">
                           <h4>
                               Donec vulputate
                           </h4>
                           <div class="text-right">8.8.2023</div>
                       </div>
                       <div class="card-body bg-info">
                           <div class="col-12 text-justify">
                               <p>
                                   Fusce blandit rhoncus ipsum, ut efficitur orci aliquam eget. Curabitur ante erat, pellentesque sed leo id, hendrerit ullamcorper libero. Donec vulputate eu erat at sollicitudin. Nam et dui lacinia, hendrerit odio at, faucibus massa. Nam eget molestie velit. Suspendisse eu felis elit. Proin at nibh sit amet turpis suscipit fermentum. Nullam sollicitudin sapien sed volutpat tempor. Mauris pellentesque, nibh non iaculis pulvinar, augue purus congue lacus, a feugiat risus enim eget tellus. Duis commodo auctor nunc et iaculis. Pellentesque nisl ante, tincidunt in aliquet sed, convallis in dolor. Nullam ullamcorper orci quis ex ornare porttitor. Cras placerat elit ac est semper, non fringilla dui efficitur. Aliquam erat volutpat. Integer a turpis nulla.
                               </p>
                           </div>
                           <div>
                               <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#info3">Zjistit více</button>
                               <div class="text-justify col-12 text-light bg-info rounded collapse" id="info3" >
                                   Nulla vehicula lacinia diam nec bibendum. Integer et purus maximus, placerat justo nec, pellentesque ipsum. Morbi est purus, tincidunt id suscipit nec, consequat at ipsum. Vestibulum mollis, elit ac maximus tincidunt, nibh augue viverra augue, eu mollis mi neque a sem.
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
    </div>
    <div class="row py-3">
        <div class="col-xl-6 cols-lg-6 col-md-12 col-sm-12">
            <p class="odstavecPrvniPismeno">
                Fusce ac bibendum nulla. Aliquam malesuada odio ut diam aliquam, ut fermentum est faucibus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque ultricies porta nisl quis varius. Nunc condimentum nisl sit amet consectetur vestibulum. Curabitur lectus libero, lacinia et rhoncus nec, hendrerit a mauris. Ut et vehicula mi. Mauris nec leo elementum, auctor enim id, tempor urna. Curabitur ut placerat tortor. Suspendisse mauris arcu, ullamcorper nec viverra eu, rutrum mattis orci. Suspendisse potenti. Integer at aliquam nibh. Donec efficitur luctus odio, vitae varius quam volutpat vel. Suspendisse vitae massa hendrerit, pellentesque dui in, gravida diam. Duis dolor mi, condimentum eget lacinia sit amet, facilisis sed eros.
            </p>
        </div>
        <div class="col-xl-6 cols-lg-6 col-md-12 col-sm-12">
           <p>
               Morbi fermentum cursus nisi, non luctus leo mollis at. Mauris sit amet nibh bibendum, gravida nibh sed, ultricies ipsum. Nullam nec sem vel sem bibendum vehicula. Morbi mi lectus, finibus eget justo a, mollis facilisis augue. Vivamus lobortis lacus sit amet ante congue condimentum. Duis tristique ex lobortis tortor hendrerit rhoncus. Phasellus tristique vel quam et ultrices. Integer suscipit finibus odio et laoreet. Cras lectus sapien, porttitor luctus lacus congue, condimentum feugiat dui. Aenean mollis imperdiet orci vel vestibulum. Quisque tempor quis lacus id ornare. Nam vulputate feugiat tincidunt. Proin eu egestas risus, eget sagittis dolor. Integer non hendrerit augue. Pellentesque nec scelerisque libero.
           </p>
        </div>
    </div>

    <!-- Carousel-->
    <div class="container">
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ul class="carousel-indicators">
                <li data-target="#carousel" data-slide-to="0" class="active"></li>
                <li data-target="#carousel" data-slide-to="1"></li>
                <li data-target="#carousel" data-slide-to="2"></li>
            </ul>

            <!-- The slideshow -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/forest.jpg" alt="">
                </div>
                <div class="carousel-item">
                    <img src="images/mountain2.jpg" alt="">
                </div>
                <div class="carousel-item">
                    <img src="images/udoli2.jpg" alt="">
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#carousel" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#carousel" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
    </div>


    <div class="row py-3">
        <div class="col-12 text-light font-italic text-center">
            *Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi finibus finibus finibus. Duis at nunc commodo, mollis turpis eu, commodo massa. Aliquam velit mi, laoreet et viverra eget, suscipit sed massa.
        </div>
    </div>
</div>
<?php
// paticka
$tplHeaders->getHTMLFooter()

?>