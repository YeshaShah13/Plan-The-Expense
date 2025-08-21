pipeline {
    agent any

    // Parameters
    parameters {
        string(name: 'PROJECT_PORT', defaultValue: '8000', description: 'Port for Apache server')
        choice(name: 'ACTION', choices: ['Build & Deploy', 'Stop'], description: 'Choose action for this run')
    }

    environment {
        COMPOSE_FILE = "docker-compose.yml"
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Checking out code..."
                checkout scm
            }
        }

        stage('Deploy Docker Containers') {
            steps {
                script {
                    if (params.ACTION == 'Build & Deploy') {
                        echo "Stopping existing containers (if any)..."
                        sh "docker-compose -f ${env.COMPOSE_FILE} down || true"

                        echo "Starting containers on port ${params.PROJECT_PORT}..."
                        sh """
                        # Update port dynamically in docker-compose if needed
                        sed -i 's/\\([0-9]*\\):80/${params.PROJECT_PORT}:80/' ${env.COMPOSE_FILE} || true

                        docker-compose -f ${env.COMPOSE_FILE} up -d --build
                        """
                    } else if (params.ACTION == 'Stop') {
                        echo "Stopping containers..."
                        sh "docker-compose -f ${env.COMPOSE_FILE} down || true"
                    }
                }
            }
        }

        stage('Deployment Status') {
            steps {
                echo "Deployment completed!"
                echo "Access your project at: http://localhost:${params.PROJECT_PORT}"
            }
        }
    }

    post {
        always {
            echo "Pipeline finished."
        }
    }
}
