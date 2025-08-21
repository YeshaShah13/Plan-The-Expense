pipeline {
    agent any

    parameters {
        string(name: 'PROJECT_PORT', defaultValue: '8000', description: 'Port for Apache server')
    }

    environment {
        COMPOSE_FILE = "docker-compose.yml"  // Path in repo root
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Checking out repository..."
                checkout scm
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    echo "Stopping existing containers (if any)..."
                    sh "docker-compose -f ${env.COMPOSE_FILE} down || true"

                    echo "Starting containers on port ${params.PROJECT_PORT}..."
                    sh """
                        # Replace port mapping dynamically
                        sed -i 's/\\([0-9]*\\):80/${params.PROJECT_PORT}:80/' ${env.COMPOSE_FILE} || true
                        docker-compose -f ${env.COMPOSE_FILE} up -d --build
                    """
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
