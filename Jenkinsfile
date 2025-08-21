pipeline {
    agent any

    // Parameters to dynamically choose project
    parameters {
        string(name: 'PROJECT_NAME', defaultValue: 'php-docker-project', description: 'Name of your project')
        string(name: 'PROJECT_PORT', defaultValue: '8080', description: 'Port for Apache server')
    }

    environment {
        COMPOSE_FILE = "${params.PROJECT_NAME}/docker-compose.yml"
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Checking out project ${params.PROJECT_NAME}"
                checkout scm
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    echo "Stopping existing containers (if any)..."
                    sh "docker-compose -f ${env.COMPOSE_FILE} down || true"

                    echo "Starting containers for ${params.PROJECT_NAME} on port ${params.PROJECT_PORT}..."
                    sh """
                    # Update port dynamically in docker-compose if needed
                    sed -i 's/\\([0-9]*\\):80/${params.PROJECT_PORT}:80/' ${env.COMPOSE_FILE} || true

                    docker-compose -f ${env.COMPOSE_FILE} up -d --build
                    """
                }
            }
        }

        stage('Deployment Status') {
            steps {
                echo "Deployment completed for ${params.PROJECT_NAME}!"
                echo "Access your project at: http://localhost:${params.PROJECT_PORT}"
            }
        }
    }

    post {
        always {
            echo "Pipeline finished for ${params.PROJECT_NAME}."
        }
    }
}
