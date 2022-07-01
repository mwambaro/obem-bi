    
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="shadow p-3 mb-5 bg-body rounded"> 
                    <div class="text-center" 
                         style="background-color: #303037; color: #ededed; padding: 10px; border-radius: 5px">
                        <h3> <?php echo $home_main_data['institution_objective']; ?>  </h3> 
                        <p>
                            <?php echo $home_main_data['institution_objective_quote']; ?>
                        </p>
                    </div>
                    <div class="d-flex flex-row justify-content-center" id="obem_home_page_main_intro_section">
                        <div style="margin: 5px">
                            <img src="<?php echo $home_main_data['institution_objective_image']; ?>" 
                                 class="img-fluid home-page-image" 
                                 width="700"
                                 style="border-radius: 5px" />
                        </div>
                        <div class="d-flex flex-column justify-content-center" 
                             style="background-color: #303037; color: #ededed; padding: 5px; margin: 5px; border-radius: 5px">
                            <p>
                                <?php echo $home_main_data['institution_address']; ?>
                            </p>
                            <p>
                                <?php echo $home_main_data['institution_objective_enterprises_quote']; ?>
                            </p>
                            <p>
                                <?php echo $home_main_data['institution_objective_unemployed_quote']; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="container-fluid" style="margin-top: 10px">
                    <div class="justify-content-center align-items-center">
                        <div class="d-flex flex-row justify-content-center secondary-content">
                            <div>
                                <div style="margin: 5px" data-aos="fade-up" class="shadow p-3 mb-5 bg-body rounded text-center">
                                    <h3 style="background-color: #303037; color: #ededed; padding: 5px; border-radius: 5px"> 
                                        <?php echo $home_main_data['institution_data_collection_experience']; ?> 
                                    </h3> 
                                    <img src="<?php echo $home_main_data['institution_data_collection_image']; ?>" 
                                        class="img-fluid home-page-image"
                                        style="border-radius: 5px" />
                                </div>
                            </div>
                            <div>
                                <div style="margin: 5px" data-aos="fade-up" class="shadow p-3 mb-5 bg-body rounded text-center">
                                    <h3 style="background-color: #303037; color: #ededed; padding: 5px; border-radius: 5px"> 
                                        <?php echo $home_main_data['institution_candidate_information_persistence']; ?>
                                    </h3> 
                                    <img src="<?php echo $home_main_data['institution_candidate_information_persistence_image']; ?>" 
                                        class="img-fluid home-page-image"
                                        style="border-radius: 5px" />
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-center secondary-content">
                            <div>
                                <div style="margin: 5px" data-aos="fade-up" class="shadow p-3 mb-5 bg-body rounded text-center">
                                    <h3 style="background-color: #303037; color: #ededed; padding: 5px; border-radius: 5px"> 
                                        <?php echo $home_main_data['institution_partnerships_experience']; ?> 
                                    </h3> 
                                    <img src="<?php echo $home_main_data['institution_partnerships_image']; ?>" 
                                        class="img-fluid home-page-image"
                                        style="border-radius: 5px" />
                                </div>
                            </div>
                            <div>
                                <div style="margin: 5px" data-aos="fade-up" class="shadow p-3 mb-5 bg-body rounded text-center">
                                    <h3 style="background-color: #303037; color: #ededed; padding: 5px; border-radius: 5px"> 
                                        <?php echo $home_main_data['institution_job_creation_experience']; ?>
                                    </h3> 
                                    <img src="<?php echo $home_main_data['institution_job_creation_image']; ?>" 
                                        class="img-fluid home-page-image"
                                        style="border-radius: 5px" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>