<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Fetch data from DB
$education = getAll($pdo, 'education', 'duration DESC');
$skills = getAll($pdo, 'skills', 'category ASC, name ASC');
$projects = getAll($pdo, 'projects', 'created_at DESC');

// Group skills by category
$groupedSkills = [];
foreach ($skills as $skill) {
    $groupedSkills[$skill['category']][] = $skill;
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ddamba Brighton Paul | Software Developer</title>
    <meta name="description"
        content="Portfolio of Ddamba Brighton Paul, a Computer Science Student and Software Developer.">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="profile-styles.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Space+Grotesk:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="background-animation"></div>

    <nav class="navbar glass">
        <div class="logo">DBP<span class="accent">.</span></div>
        <ul class="nav-links">
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#skills">Skills</a></li>
            <li><a href="#experience">Experience</a></li>
            <li><a href="#projects">Projects</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="nav-controls">
            <button id="theme-toggle" aria-label="Toggle Dark Mode">
                <i class="fas fa-moon"></i>
            </button>
            <div class="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
        </div>
    </nav>

    <header id="home" class="hero">
        <div class="hero-content">
            <p class="greeting fade-in">Hello, I'm</p>
            <h1 class="name slide-up">Ddamba Brighton Paul</h1>
            <h2 class="role slide-up delay-1">I am a <span class="typing-text"></span></h2>
            <p class="hero-description slide-up delay-2">
                Enthusiastic Computer Science student at Busitema University.
                Passionate about building software, troubleshooting systems, and creating user-friendly web interfaces.
            </p>
            <div class="hero-buttons slide-up delay-3">
                <a href="#contact" class="btn primary-btn">Get In Touch</a>
                <a href="#projects" class="btn secondary-btn">View Work</a>
            </div>
        </div>
        <div class="hero-visual fade-in delay-2">
            <div class="profile-container">
                <div class="profile-glow"></div>
                <div class="profile-border"></div>
                <img src="images/profile.jpg" alt="Ddamba Brighton Paul" class="profile-img">

                <!-- Floating tech badges -->
                <div class="tech-badge badge-1 glass">
                    <i class="fab fa-java"></i>
                </div>
                <div class="tech-badge badge-2 glass">
                    <i class="fas fa-code"></i>
                </div>
            </div>
        </div>
    </header>

    <section id="about" class="section">
        <div class="container">
            <h2 class="section-title fade-up">About <span class="accent">Me</span></h2>
            <div class="about-grid">
                <div class="about-text glass fade-up">
                    <p>
                        I am a dedicated <strong>Computer Science student</strong> at Busitema University (Expected
                        Graduation: 2027) with a current CGPA of <strong>4.37</strong>.
                    </p>
                    <p>
                        My objective is to apply my skills in software installation, maintenance, web design, and
                        computer networks while contributing to reputable organizations. I have strong analytical skills
                        and am capable of troubleshooting and resolving technical issues efficiently.
                    </p>
                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Entebbe, Uganda</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span>Brightonpaul003@gmail.com</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span>0742956235</span>
                        </div>
                    </div>
                </div>
                <div class="education-card glass fade-up delay-1">
                    <h3><i class="fas fa-graduation-cap"></i> Education</h3>
                    <?php if (empty($education)): ?>
                        <div class="timeline-item">
                            <span class="date">Expected 2027</span>
                            <h4>Bachelor of Science in Computer Science</h4>
                            <p class="institution">Busitema University</p>
                            <p class="gpa">Current CGPA: 4.37</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($education as $edu): ?>
                        <div class="timeline-item">
                            <span class="date"><?php echo $edu['duration']; ?></span>
                            <h4><?php echo $edu['course']; ?></h4>
                            <p class="institution"><?php echo $edu['institution']; ?></p>
                            <p><?php echo nl2br($edu['description']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="skills" class="section">
        <div class="container">
            <h2 class="section-title fade-up">Technical <span class="accent">Skills</span></h2>

            <div class="skills-grid">
                <?php if (empty($groupedSkills)): ?>
                    <!-- Fallback content if DB is empty -->
                    <div class="skill-category glass fade-up">
                        <h3><i class="fas fa-code"></i> Programming & Web</h3>
                        <div class="skill-tags">
                            <span class="tag">Java</span>
                            <span class="tag">JavaScript</span>
                            <span class="tag">PHP</span>
                        </div>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach($groupedSkills as $category => $categorySkills): 
                    ?>
                    <div class="skill-category glass fade-up <?php echo $delay > 0 ? 'delay-'.$delay : ''; ?>">
                        <h3><i class="<?php echo $categorySkills[0]['icon_class'] ?: 'fas fa-tools'; ?>"></i> <?php echo $category; ?></h3>
                        <div class="skill-tags">
                            <?php foreach($categorySkills as $skill): ?>
                            <span class="tag" title="<?php echo $skill['proficiency']; ?>% proficiency"><?php echo $skill['name']; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php 
                    $delay++;
                    endforeach; 
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="experience" class="section">
        <div class="container">
            <h2 class="section-title fade-up">Experience & <span class="accent">Leadership</span></h2>

            <div class="timeline">
                <div class="timeline-block fade-up">
                    <div class="marker"></div>
                    <div class="timeline-content glass">
                        <h3>Data Entry & Computer Maintenance</h3>
                        <span class="date">May 2024 – July 2024 (Recess Term)</span>
                        <ul>
                            <li>Troubleshot computers, routers, and cables; performed repairs and replacements.</li>
                            <li>Managed inventory tracking and organized desktop workspaces.</li>
                            <li>Connected RJ45 data cables and ensured network connectivity.</li>
                            <li>Handled data verification and integrity checks.</li>
                        </ul>
                    </div>
                </div>

                <div class="timeline-block fade-up delay-1">
                    <div class="marker"></div>
                    <div class="timeline-content glass">
                        <h3>Class Coordinator</h3>
                        <span class="date">2023 – Present | Busitema University</span>
                        <p>Organize class activities and facilitate communication between students and faculty members.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="projects" class="section">
        <div class="container">
            <h2 class="section-title fade-up">Featured <span class="accent">Projects</span></h2>
            <div class="projects-grid">

                <?php if (empty($projects)): ?>
                    <p class="fade-up">Projects are being updated. Check back soon!</p>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach($projects as $project): 
                    ?>
                    <div class="project-card glass fade-up <?php echo $delay > 0 ? 'delay-'.$delay : ''; ?>">
                        <div class="project-image">
                            <?php if ($project['image_path']): ?>
                                <img src="<?php echo $project['image_path']; ?>" alt="<?php echo $project['title']; ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px 12px 0 0;">
                            <?php else: ?>
                                <div class="placeholder-img code-img">
                                    <i class="fas fa-laptop-code"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="project-content">
                            <h3><?php echo $project['title']; ?></h3>
                            <p class="tech-stack"><?php echo str_replace(',', ' • ', $project['technologies']); ?></p>
                            <p><?php echo $project['description']; ?></p>
                            <div class="project-links">
                                <?php if ($project['link']): ?>
                                    <a href="<?php echo $project['link']; ?>" target="_blank" class="btn-sm">View Work</a>
                                <?php endif; ?>
                                <?php if ($project['github_link']): ?>
                                    <a href="<?php echo $project['github_link']; ?>" target="_blank" class="btn-sm outline"><i class="fab fa-github"></i> Code</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $delay++;
                    endforeach; 
                    ?>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title fade-up">Get In <span class="accent">Touch</span></h2>
            <div class="contact-wrapper glass fade-up">
                <div class="contact-info">
                    <h3>Let's Connect</h3>
                    <p>Feel free to reach out for collaborations or opportunities.</p>
                    <div class="contact-details">
                        <a href="mailto:Brightonpaul003@gmail.com" class="contact-item">
                            <i class="fas fa-envelope accent-icon"></i>
                            <span>Brightonpaul003@gmail.com</span>
                        </a>
                        <a href="tel:0742956235" class="contact-item">
                            <i class="fas fa-phone accent-icon"></i>
                            <span>0742956235</span>
                        </a>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt accent-icon"></i>
                            <span>Entebbe, Uganda</span>
                        </div>
                    </div>

                    <div class="references">
                        <h4>Reference</h4>
                        <p><strong>Dr. Lukyamuzi Andrew</strong><br>Acting Head of Computer Department<br>Busitema
                            University<br>0772547745</p>
                    </div>

                    <div class="social-links">
                        <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-github"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <form id="contactForm" class="contact-form">
                    <div id="formResponse" style="margin-bottom: 20px; display: none; padding: 10px; border-radius: 8px;"></div>
                    <div class="form-group">
                        <input type="text" name="name" id="name" required placeholder=" ">
                        <label for="name">Your Name</label>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="email" required placeholder=" ">
                        <label for="email">Your Email</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" id="subject" required placeholder=" ">
                        <label for="subject">Subject</label>
                    </div>
                    <div class="form-group">
                        <textarea name="message" id="message" rows="5" required placeholder=" "></textarea>
                        <label for="message">Your Message</label>
                    </div>
                    <button type="submit" class="btn primary-btn block-btn">Send Message</button>
                    <div class="loading-spinner" style="display: none; text-align: center; margin-top: 10px;">
                        <i class="fas fa-spinner fa-spin"></i> Sending...
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Ddamba Brighton Paul. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
    <script>
        // Handle AJAX form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const responseDiv = document.getElementById('formResponse');
            const spinner = form.querySelector('.loading-spinner');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            const formData = new FormData(form);
            
            responseDiv.style.display = 'none';
            spinner.style.display = 'block';
            submitBtn.disabled = true;
            
            fetch('contact_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                spinner.style.display = 'none';
                submitBtn.disabled = false;
                responseDiv.style.display = 'block';
                responseDiv.innerText = data.message;
                
                if (data.status === 'success') {
                    responseDiv.style.background = 'rgba(46, 204, 113, 0.2)';
                    responseDiv.style.color = '#2ecc71';
                    form.reset();
                } else {
                    responseDiv.style.background = 'rgba(231, 76, 60, 0.2)';
                    responseDiv.style.color = '#e74c3c';
                }
            })
            .catch(error => {
                spinner.style.display = 'none';
                submitBtn.disabled = false;
                responseDiv.style.display = 'block';
                responseDiv.style.background = 'rgba(231, 76, 60, 0.2)';
                responseDiv.style.color = '#e74c3c';
                responseDiv.innerText = 'Something went wrong. Please try again.';
            });
        });
    </script>
</body>

</html>
