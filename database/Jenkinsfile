pipeline {
    agent any

    stages {
        stage('Git Clone') {
            steps{
                git branch: 'main', 
                credentialsId: 'github_credential', 
                url: 'https://github.com/phokhaing/devops_ci_admin.git'
            }
        }
        stage('Docker build'){
            steps{
                sh "sed -i \"s/TAG_VERSION/$BUILD_NUMBER/g\" docker-compose.yml"
                sh "docker-compose build"
            }
        }
        
        stage('Push Image to Docker Hub'){
            steps{
                echo "Push phokhaing/ci_admin_app to docker hub"
                withCredentials([string(credentialsId: 'dockerhub-pwd', variable: 'dokerhub_pwd')]) {
                    sh 'docker login -u phokhaing -p $dokerhub_pwd'
                }
                sh "docker push phokhaing/ci_admin_app:$BUILD_NUMBER"
            }
        }
        
        stage('Deploy on kubernetes cluster'){
            steps{
                sshagent(['kubernetes_master']) {
                    echo "copy deploy files into server kubernetes_master"
                    sh "scp -o StrictHostKeyChecking=no app-deploy.yaml ubuntu@54.151.164.253:/home/ubuntu"
                    sh "scp -o StrictHostKeyChecking=no mysql-secret.yaml ubuntu@54.151.164.253:/home/ubuntu"
                    sh "scp -o StrictHostKeyChecking=no mysql-pvc.yaml ubuntu@54.151.164.253:/home/ubuntu"
                    sh "scp -o StrictHostKeyChecking=no mysql-deploy.yaml ubuntu@54.151.164.253:/home/ubuntu"
                    
                    echo "use sed to find and replace text TAG_VERSION"
                    sh "ssh ubuntu@54.151.164.253 sed -i \"s/TAG_VERSION/$BUILD_NUMBER/g\" app-deploy.yaml"
                    
                    echo "Deploy Application"
                    script{
                        try{
                            echo "Deploy Database"
                            sh "ssh ubuntu@54.151.164.253 kubectl apply -f mysql-secret.yaml"
                            sh "ssh ubuntu@54.151.164.253 kubectl apply -f mysql-pvc.yaml"
                            sh "ssh ubuntu@54.151.164.253 kubectl apply -f mysql-deploy.yaml"
                            echo "Deploy App"
                            sh "ssh ubuntu@54.151.164.253 kubectl apply -f app-deploy.yaml"
                            
                        }catch(error){
                            echo "Deploy Database"
                            sh "ssh ubuntu@54.151.164.253 kubectl create -f mysql-secret.yaml"
                            sh "ssh ubuntu@54.151.164.253 kubectl create -f mysql-pvc.yaml"
                            sh "ssh ubuntu@54.151.164.253 kubectl create -f mysql-deploy.yaml"
                            echo "Deploy App"
                            sh "ssh ubuntu@54.151.164.253 kubectl create -f app-deploy.yaml"
                        }
                    }
                }
            }
        }
    }
}
