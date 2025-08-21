pipeline {
    agent any

    environment {
        // If using Docker Desktop on Windows, DOCKER_HOST is not needed.
        // If using TCP, uncomment the line below:
        // DOCKER_HOST = "tcp://host.docker.internal:2375"
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo 'Checking out code...'
                git branch: 'main', url: 'https://github.com/YeshaShah13/Plan-The-Expense.git', credentialsId: 'dockerhub'
            }
        }

        stage('Build and Deploy Docker Containers') {
            steps {
                echo 'Stopping existing containers (if any)...'
                bat 'docker-compose -f docker-compose.yml down'

                echo 'Building and starting containers...'
                bat 'docker-compose -f docker-compose.yml up -d --build'
            }
        }

        stage('Deployment Status') {
            steps {
                echo 'Containers should be running. Check with: docker ps'
            }
        }
    }

    post {
        success {
            echo 'Pipeline finished successfully!'
        }
        failure {
            echo 'Pipeline failed. Check console output for errors.'
        }
    }
}
