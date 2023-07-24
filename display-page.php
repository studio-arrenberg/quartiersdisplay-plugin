
<?php get_header(); ?>

<div id="qd-landingpage" class="qd" role="main">

    <main>
        <h1>    
            Dein Viertel hat jetzt<br />
            <span class="">Quartiersdisplays</span>
        </h1>
        <img src="<?php echo plugins_url( 'includes/assets/images/quartiersdisplay-illustration.png', __FILE__ ); ?>" alt="Quartiersdisplays" />
    </main>

    <section>
        <div class="card qp-card">
            <div class="qp-card-content">
                <h2 class="heading-1">Unsichtbares sichtbar machen</h2>
                <p>Wir machen das Quartiersgeschehen für Dich sichtbar und geben lokalen Akteuren ihre Bühne.</p>                    

                <a href="https://arrenberg.app/projekte/digitale-strassenschilder-am-arrenberg/" class="button is-primary">
                    Projekt entdecken 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <div class="accordion-group">
                <div class="accordion-item">
                    <div class="accordion-trigger">
                        <div class="accordion-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <!-- minus icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </div>
                        <span>Was ist ein Quartiersdisplay?</span>
                    </div>
                    <div class="accordion-content">
                        <p>Ein Quartiersdisplay ist ein kleines, digitales Schild, das in deinem Viertel aufgestellt wird. Es zeigt dir, was gerade in deinem Viertel passiert. Du kannst es auch selbst nutzen, um deine Veranstaltungen, Projekte oder Nachrichten zu veröffentlichen.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-trigger">
                        <div class="accordion-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <!-- minus icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </div>
                        <span>Wo stehen die Displays?</span>
                    </div>
                    <div class="accordion-content">
                        <p>Wir haben bisher vier Quartiersdisplays an öffentlichen Plätzen auf dem Arrenberg verteilt.</p>
                        <img src="<?php echo plugins_url( 'includes/assets/images/Standorte.png', __FILE__ ); ?>" alt="Quartiersdisplays" />
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-trigger">
                        <div class="accordion-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <!-- minus icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </div>
                        <span>Wie kann ich Quartiersdisplays nutzen?</span>
                    </div>
                    <div class="accordion-content">
                        <p>Um Quartiersdisplays nutzen zu können, musst du dich zuerst registrieren. Dann ein Projekt erstellen. Jetzt kannst du deine Veranstaltungen, Projekte und Nachrichten veröffentlichen.</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var acc = document.getElementsByClassName("accordion-item");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function() {
                    this.classList.toggle("open");
                });
            }
        </script>
 
        
        <div class="qd-grid">
            <div class="card ">
                <a class="card-link" href="<?php echo get_site_url(); ?>/projektverzeichnis ">
                    <h3>Quartiersprojekte</h3>
                    <div class="card-emoji">🎨</div>
                    <button>Entdecken</button>
                </a>
            </div>

            <div class="card">
                <a class="card-link" href="<?php echo get_site_url(); ?>/veranstaltungen ">
                    <h3>Veranstaltungen</h3>
                    <div class="card-emoji">🗓</div>
                    <button>Entdecken</button>
                </a>
            </div>
        </div>

        <?php 
            $image = get_field('quartier_image', 'option');
            if (empty( $image )) {
                $image = get_template_directory_uri()."/assets/images/quartier.png";
            }
            else {
                $image = $image['url'];
            }
        ?>

        <div class="card card-quartier " href="<?php echo get_site_url(); ?>/veranstaltungen" style="background: url('<?php echo esc_url($image); ?>')">
            <div class="card-quartier-overlay"></div>
            <a class="card-link " href="<?php echo get_site_url(); ?>">
                <h3 class="heading-size-1">Arrenberg</h3>
                <button>Quartier kennenlernen</button>
            </a>
        </div>
        
        <?php
            $text = __('Teile uns dein Feedback mit. Wir arbeiten kontinuierlich an den Displays und wollen sie für Dich perfekt machen.','quartiersplattform');
            reminder_card('', __('Feedback zu den Quartiersdisplays','quartiersplattform'), $text, __('Zur Wunschliste','quartiersplattform'), home_url().'/feedback' );
        ?>

        <div class="projektpartner">
            <h2>Vielen Dank an unsere Projektpartner</h2>
            <div class="projektpartner-logos">
                <a class="projektpartner-logo" href="https://www.proviel.de/">
                    <img src="<?php echo plugins_url( 'includes/assets/logos/proviel.svg', __FILE__ ); ?>" alt="Proviel" />
                </a>
                <a class="projektpartner-logo" href="http://internationaler-bund.de">
                    <img src="<?php echo plugins_url( 'includes/assets/logos/ib.svg', __FILE__ ); ?>" alt="Proviel" />
                </a>
                <a class="projektpartner-logo" href="https://behindertnaund.de">
                    <img src="<?php echo plugins_url( 'includes/assets/logos/bnu.svg', __FILE__ ); ?>" alt="Proviel" />
                </a>
                <a class="projektpartner-logo" href="https://arrenberg.app/aufbruch-am-arrenberg/">
                    <img src="<?php echo plugins_url( 'includes/assets/logos/aaa.svg', __FILE__ ); ?>" alt="Proviel" />
                </a>

            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>