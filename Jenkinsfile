#!groovy

pipeline {

  agent any

  stages {

    stage('Deploy sources') {
      steps {
        sh 'ssh plank@54.38.243.195 \
        -p 22099 \
        -o StrictHostKeyChecking=no \
        " \
        cd /home/plank/ \
        && touch ok.test \
        "'
      }
    }
  }

  post {

    success {
      echo "Success !"
    }
    unstable {
      echo "Unstable !"
    }
    failure {
      echo "Failure !"
    }
  }
}
