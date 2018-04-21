#!groovy

pipeline {

  agent any

  stages {

    stage('Fetch sources') {
      steps {
        sh 'ssh plank@54.38.243.195 \
        -p 22099 \
        -o StrictHostKeyChecking=no \
        " \
        cd /home/plank/plankprod.com \
        && git clone git@github.com:dom18fr/plankprod.git deploy \
        "'
      }
    }
  }

  post {
    success {
      echo "Success !"
      sh 'ssh plank@54.38.243.195 \
        -p 22099 \
        -o StrictHostKeyChecking=no \
        " \
        rm -rf /home/plank/plankprod.com/previous \
        && mv /home/plank/plankprod.com/current /home/plank/plankprod.com/previous \
        && mv /home/plank/plankprod.com/deploy /home/plank/plankprod.com/current \
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
