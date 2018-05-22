#!groovy

pipeline {

  agent any

  stages {

    stage('Fetch sources') {
      steps {
        sh 'ssh planck@54.38.243.195 \
        -p 22099 \
        -o StrictHostKeyChecking=no \
        " \
        cd /home/planck/planckprod.com \
        && git clone git@github.com:dom18fr/plankprod.git deploy \
        "'
      }
    }

    stage('Build Drupal sources') {
      steps {
        sh 'ssh planck@54.38.243.195 \
        -p 22099 \
        -o StrictHostKeyChecking=no \
        " \
        cd /home/planck/planckprod.com/deploy/drupal \
        && /home/planck/bin/composer install --no-interaction \
        && ln -s /home/planck/planckprod.com/shared/settings.local.php /home/planck/planckprod.com/deploy/drupal/web/sites/default/settings.local.php \
        && ln -s /home/planck/planckprod.com/shared/files /home/planck/planckprod.com/deploy/drupal/web/sites/default/files \
        "'
      }
    }

    stage('Update Drupal') {
      steps {
        sh 'ssh planck@54.38.243.195 \
        -p 22099 \
        -o StrictHostKeyChecking=no \
        " \
        cd /home/planck/planckprod.com/deploy/drupal/web \
        && /home/planck/bin/drush cr \
        && /home/planck/bin/drush cim -y \
        && /home/planck/bin/drush updb -y \
        && /home/planck/bin/drush cr \
        "'
      }
    }
  }

  post {
    success {
      echo "Success !"
      sh 'ssh planck@54.38.243.195 \
        -p 22099 \
        -o StrictHostKeyChecking=no \
        " \
        rm -rf /home/planck/planckprod.com/previous \
        && mv /home/planck/planckprod.com/current /home/planck/planckprod.com/previous \
        && mv /home/planck/planckprod.com/deploy /home/planck/planckprod.com/current \
        "'
    }
    unstable {
      echo "Unstable !"
    }
    failure {
      sh 'ssh planck@54.38.243.195 \
        -p 22099 \
        -o StrictHostKeyChecking=no \
        " \
        rm -rf /home/planck/planckprod.com/deploy \
        "'
    }
  }
}
