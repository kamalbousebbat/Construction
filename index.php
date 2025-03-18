<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Construction Website</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styleB.css" />
  </head>
  <body>
    <div class="container">
      <header>
        <div class="logo">
          <img
            src="img/construction-logo-design-template-5fae5b8a85c03a5d4518b1494e155d59_screen.jpg"
            alt=""
          />
          <h1>Construct</h1>
        </div>
        <nav>
          <div class="listemenu">
            <button id="homePage" style="background-color: rgb(255, 127, 42);">Home</button>
            <button id="servicePage">Services</button>
            <button id="projectPage">Projects</button>
            <button id="aboutUs">About Us</button>
            <button class="contact" id="contactPage">Contact us</button>
          </div>
          <div id="barre">
            <div id="transbarre"></div>
          </div>
          <div id="menuicon">
            <i id="iconbarre" class="fa-solid fa-bars"></i>
            <div class="listemenusmall">
              <button id="homePages" style="background-color: rgb(255, 127, 42);">Home</button>
              <button id="servicePages">Services</button>
              <button id="projectPages">Projects</button>
              <button id="aboutUss">About Us</button>
              <button class="contact" id="contactPages">Contact us</button>
            </div>
          </div>
        </nav>
      </header>
      <main>
        <section class="Homepage">
          <div style="width: 75%;margin: auto; min-height: 100vh;">
            <h1>create The Constructions You Want Here</h1>
            <p>
              we provide the best home design construction and maintenance
              servicces for you and you family
            </p>
            <div class="homebutton">
              <div class="contact">Our Services</div>
              <div id="button-project">View Projects</div>
            </div>
            <div class="information">
              <span>
                <div>10+</div>
                 <div class="secinf">
                  Years of <br />experience
                 </div>
              </span>
              <span>
                <div>200</div>
                <div class="secinf">Complete <br />
                  Projects</div>
              </span>
            </div>
          </div>
        </section>
        <section class="aboutus">
          <div class="containerab">
            <div class="imgcontainer">
              <img src="img/about-us.jpg" alt="" class="img1" />
              <img src="img/aboutus1.jpg" alt="" class="img2" />
            </div>
            <div class="informations">
              <h2>About Us</h2>
              <h1>We Provide The Best Service To Build</h1>
              <p>
                We strive to provide the best professionals to make your project
                a construction masterpiece, somthing unique and ummatched
              </p>
              <div class="positive">
                <div>
                  <i class="child fa-regular fa-circle-check"></i>
                  <span>professional workers</span>
                </div>
                <div>
                  <i class="child fa-regular fa-circle-check"></i>
                  <span>Extenssion experience</span>
                </div>
                <div>
                  <i class="child fa-regular fa-circle-check"></i>
                  <span> Guaranteed quality </span>
                </div>
                <div>
                  <i class="child fa-regular fa-circle-check"></i>
                  <span> We quote your project </span>
                </div>
              </div>
              <div class="buttonproject">View Project</div>
            </div>
          </div>
        </section>
        <section class="Servicepage">
          <div class="headservice">
            <h1>OUR SERVICES</h1>
          </div>
          <div class="description">
            <div class="colone">
              
              <h1>Hight Quality Construction Services</h1>
            </div>
            <div class="coltwo">
              <p style="font-size: 20px;">
                We provide multiple services for you, offering confidence and
                security in construction
              </p>
            </div>
            <div class="colthree">
              <div id="contactfrservice">contact Now</div>
            </div>
          </div>
          <div class="services">
            <div class="servone service">
              <i class="fa-solid fa-city"></i>
              <h2>Housing Construction</h2>
              <p>
                We build with best professionals and high quality work for a
                safe effective home.
              </p>
            </div>
            <div class="service servtwo">
              <i class="fa-solid fa-house-laptop"></i>
              <h2>Construction Of Home Areas</h2>
              <p>
                We build with best professionals and high quality work for a
                safe effective home.
              </p>
            </div>
            <div class="servthree service">
              <i class="fa-solid fa-screwdriver-wrench"></i>
              <h2>Maintenance & Repair</h2>
              <p>
                We build with best professionals and high quality work for a
                safe effective home.
              </p>
            </div>
            <div class="servfoor service">
              <i class="fa-solid fa-cube"></i>
              <h2>Installation Of ceramics & Others</h2>
              <p>
                We build with best professionals and high quality work for a
                safe effective home.
              </p>
            </div>
            <div class="servfive service">
              <i class="fa-solid fa-droplet"></i>
              <h2>Water & Drainage Installation</h2>
              <p>
                We build with best professionals and high quality work for a
                safe effective home.
              </p>
            </div>
          </div>
          <div class="flecheicons">
            <i id="flechleft" class="fa-solid fa-arrow-left-long"></i>
            <i id="flechright" class="fa-solid fa-arrow-right"></i>
          </div>
        </section>
        <section class="projectpage">
          <h4>OUR PROJECTS</h4>
          <h2>Latest Completed Projects</h2>
          <div class="projects">
            <?php
                require_once 'conf.php';
                $sql = "SELECT * FROM projects"; // Sélectionner les colonnes souhaitées
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Afficher les données
                    while ($row = $result->fetch_assoc()) {
                      echo "
                        <div class='pone'>
                              <img src='" . $row["image_url"] . " ' alt='' />
                              <h6>" . $row["title"] . "</h6>
                              <h1>" . $row["category"] . "</h1>
                              <h5>" . $row["project_date"] . "</h5>
                              <p>
                                " . $row["description"] . "
                              </p>
                        </div>";
                    }
                    
                } else {
                    echo "Aucun résultat trouvé.";
                }
              ?>
          </div>
        </section>
        <section class="contactpage">
          <h1>CONTACT ME</h1>
          <h2>Write To US & Build</h2>
          <div class="infocontact">
            <img src="img/materiel.jpeg" alt="" />
            <div class="perinf">
              <div>
                <i class="fa-solid fa-location-dot"></i>
                <span>I'm here</span>
                <p>
                  Peru-Lima<br />
                  Av.Moon #375
                </p>
              </div>
              <div>
                <i class="fa-solid fa-phone"></i>
                <span>Call me</span>
                <p>+212 66666666 <br />+212 88888888</p>
              </div>
              <div>
                <i class="fa-solid fa-comment-dots"></i>
                <span>Chat with me</span>
                <div id="socialicon">
                  <a href="https://www.whatsapp.com/?lang=fr_FR"><i class="fa-brands fa-whatsapp"></i></a>
                  <a href="https://www.messenger.com/?locale=fr_FR"><i class="fa-brands fa-facebook-messenger"></i></a>
                  <a href="https://web.telegram.org/k/"><i class="fa-brands fa-telegram"></i></a>
                  
                  
                  
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
      <footer>
        <div class="footerelem">
          <div class="elemone">
            <div id="imglogo">
              <img
                src="img/construction-logo-design-template-5fae5b8a85c03a5d4518b1494e155d59_screen.jpg"
                alt=""
              />
              <h3>Construct</h3>
            </div>
            <div>
              We build security <br />
              and trust in homes
            </div>
            <div>Email:const123@email.com</div>
          </div>
          <div class="elemtwo">
            <h3>Company</h3>
            <div>About US</div>
            <div>Services</div>
            <div>Projects</div>
          </div>
          <div class="elemthree">
            <h3>Information</h3>
            <div>Peru lima <br />Av.Moon#123</div>
          </div>
          <div class="elemfoor">
            <h3>Social Media</h3>
            <div class="footericons">
              <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a>
              <a href="https://github.com"><i class="fa-brands fa-github"></i></a>
              <a href="https://x.com/"><i class="fa-brands fa-twitter"></i></a>
              
            </div>
          </div>
        </div>
        <div class="copirate">
          <p>&copy; 2024 Our company.ALL rights reserved.</p>
        </div>
      </footer>
    </div>
    <script src="mai.js"></script>
  </body>
</html>
