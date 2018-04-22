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
      echo "Failure !"
    }
  }
}
