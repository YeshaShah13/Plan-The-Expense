pipeline {
    agent any

    environment {
        // Path to docker-compose in Jenkins container
        DOCKER_COMPOSE_FILE = "${WORKSPACE}/docker-compose.yml"
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Checking out code from GitHub..."
                git branch: 'main', url: 'https://github.com/YeshaShah13/Plan-The-Expense.git'
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    echo "Stopping existing containers if any..."
                    sh "docker-compose -f ${DOCKER_COMPOSE_FILE} down || true"

                    echo "Starting containers..."
                    sh "docker-compose -f ${DOCKER_COMPOSE_FILE} up -d --build"
                }
            }
        }

        stage('Deployment Status') {
            steps {
                echo "Deployment completed!"
                echo "Access your project at: http://localhost:8000"
            }
        }
    }

    post {
        always {
            echo "Pipeline finished."
        }
        failure {
            echo "Pipeline failed. Check logs for details."
        }
    }
}
