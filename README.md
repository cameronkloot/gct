# Genesis Child Theme (GCT)
A Wordpress Genesis child theme requiring the Advanced Custom Fields (ACF) plugin

### Installation
Install <a href="https://docs.docker.com/engine/installation/" target="\_blank">Docker</a> and <a href="https://docs.docker.com/compose/install/" target="\_blank">Docker Compose</a>

Grab the child theme and environment config
- `git clone https://github.com/cameronkloot/gct.git your_theme_name`
- `cd your_theme_name`
- `rm -rf .git`

Initialize a new git repo to track theme
- `git init .`
- `git add .`
- `git commit -m "Initial commit"`

Initialize and run environment
- `docker-compose up --build -d`

The site will be available at `localhost:5647`
